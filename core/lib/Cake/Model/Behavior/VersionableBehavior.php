<?php
App::import('Model', 'Revision');
/**
 * Versionable behavior
 *
 * Saves sets of changes like a version control.
 */
class VersionableBehavior extends ModelBehavior
{
    public $Revision;
    private $_model;
    private $_type;
    private $_versionedFields = array();
    private $_noFieldsError = 'Specify fields to version as behavior settings. \'Versionable\' => array(\'field1\', \'field2\')';
    private $_doVersion = true;
    function setup(Model $model, $versionedFields = array())
    {
        if (empty($versionedFields)) {
            trigger_error($this->_noFieldsError);
        }
        $this->Revision = new Revision();
        $this->_model = $model;
        $this->_type = strtolower($this->_model->name);
        $this->_versionedFields = $versionedFields;
    }
    /**
     * After save callback
     *
     * Save a new revision here.
     *
     */
    function afterSave()
    {
        if ($this->_doVersion) {
            $this->saveRevision();
        }
        return true;
    }
    function getRevision(Model $model, $nodeId, $versionNumber)
    {
        $revision = $this->getRevisionData($model, $nodeId, $versionNumber);
        $revContent = $revision['Revision']['content'];
        $revData = json_decode($revContent, true);
        $data = $model->findById($nodeId);
        // Unset the versioned fields from data
        foreach($this->_versionedFields as $field) {
            foreach($model->belongsTo as $modelname) {
                if ($modelname['foreignKey'] == $field && !empty($revData[$model->name][$modelname['foreignKey']]) && $revData[$model->name][$modelname['foreignKey']] != $data[$model->name][$field]) {
                    $newData = $model->{$modelname['className']}->find('first', array(
                        'id' => $revData[$model->name][$modelname['foreignKey']],
                        'recursive' => - 1
                    ));
                    $data[$modelname['className']] = am($data[$modelname['className']], $newData[$modelname['className']]);
                }
            }
            unset($data[$model->name][$field]);
        }
        $data['Revision']['created'] = $revision['Revision']['created'];
        $data[$model->name] = am($data[$model->name], $revData[$model->name]);
        return $data;
    }
    /**
     * Get revisions for an item
     *
     * @param Model $model
     * @param int $id
     * @param int $limit
     * @return array
     */
    function getRevisions(Model $model, $id, $limit = null)
    {
        $id = intval($id);
        $conditions = "Revision.node_id = $id AND Revision.type = '{$this->_type}'";
        $order = 'Revision.revision_number DESC';
        $revisions = $this->Revision->find('all', compact('conditions', 'order', 'limit'));
        return $revisions;
    }
    /**
     * Turn off versioning for current model
     *
     */
    function noVersion()
    {
        $this->_doVersion = false;
    }
    function revisionDiff(Model $model, $itemId = null, $revisionId)
    {
        exit('Not implemented yet.');
        $item = $model->findById($itemId);
        $revision = $this->getRevision($model, $revisionId);
        $revContent = $revision['Revision']['content'];
        $revItem = json_decode($revContent, true);
        $diff = array();
        foreach($revItem as $field => $value) {
            $diff = new Text_Diff('auto', array(
                $revision['Revision'][$field],
                $item[$model->name][$field]
            ));
            $renderer = new Text_Diff_Renderer_unified();
            $diff[$field] = $renderer->render($diff);
        }
        pr($diff);
        die();
    }
    /**
     * Stores a copy of choosen fields from the current model $data property in
     * the revisions table. (Only if something has changed from the last revision.)
     *
     */
    function saveRevision()
    {
        $nodeId = intval($this->_model->id);
        $latestRev = $this->Revision->find('first', array(
            'conditions' => array(
                'Revision.node_id' => $nodeId,
				'Revision.type' => $this->_type
            ) ,
			'order' => array(
				'Revision.revision_number' => 'desc'
			) 
        ));
        $revNo = 1;
        if (!empty($latestRev)) {
            $revNo = intval($latestRev['Revision']['revision_number']) + 1;
        }
        // Archive only the choosed fields
        $archive[$this->_model->name] = array();
        foreach($this->_versionedFields as $field) {
            if (isset($this->_model->data[$this->_model->name][$field])) {
                $archive[$this->_model->name][$field] = $this->_model->data[$this->_model->name][$field];
            }
        }
        // Encode content field
        if (!empty($archive[$this->_model->name])) {
            $archive = json_encode($archive);
            $revData['Revision'] = array(
                'type' => $this->_type,
                'node_id' => $nodeId,
                'content' => $archive,
                'revision_number' => $revNo,
                'user_id' => isset($_SESSION['Auth']['User']['id']) ? $_SESSION['Auth']['User']['id'] : ''
            );
            // Save only if the content has changed
            if (!isset($latestRev['Revision']['content']) or $latestRev['Revision']['content'] !== $revData['Revision']['content']) {
                $this->Revision->save($revData);
            }
        }
    }
    /**
     * Find a specific revision
     *
     * @param Model $model
     * @param int $id
     */
    private function getRevisionData(Model $model, $node_id, $revision_number)
    {
        $revision = $this->Revision->find('First', array(
			'conditions' => array(
				'node_id' => $node_id,
				'revision_number' => $revision_number,
				'Revision.type' => $this->_type
			)
		));
		return $revision;
    }
}
?>
