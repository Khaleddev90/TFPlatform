<?php
/**
 * TJ Platform
 *
 * PHP version 5
 *
 * @category   PHP
 * @package    TJPlatform
 * @subpackage Core
 * @author     Nicolas <nicodele8@gmail.com>
 * @copyright  2018 Nicolas 
 * 
 * 
 */
class Term extends AppModel
{
    /**
     * Model name
     *
     * @var string
     * @access public
     */
    public $name = 'Term';
    /**
     * Behaviors used by the Model
     *
     * @var array
     * @access public
     */
    public $actsAs = array(
        'Cached' => array(
            'prefix' => array(
                'term_',
                'node_',
                'nodes_',
                'cms_nodes_',
                'cms_vocabularies_',
                'cms_vocabulary_',
            ) ,
        ) ,
    );
    /**
     * Model associations: hasAndBelongsToMany
     *
     * @var array
     * @access public
     */
    public $hasAndBelongsToMany = array(
        'Vocabulary' => array(
            'className' => 'Vocabulary',
            'with' => 'Taxonomy',
            'joinTable' => 'taxonomy',
            'foreignKey' => 'term_id',
            'associationForeignKey' => 'vocabulary_id',
            'unique' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'finderQuery' => '',
            'deleteQuery' => '',
            'insertQuery' => '',
        ) ,
    );
    /**
     * Save Term and return ID.
     * If another Term with same slug exists, return ID of that Term without saving.
     *
     * @param  array $data
     * @return integer
     */
    public function saveAndGetId($data)
    {
        $term = $this->find('first', array(
            'conditions' => array(
                'Term.slug' => $data['slug'],
            ) ,
        ));
        if (isset($term['Term']['id'])) {
            return $term['Term']['id'];
        }
        $this->id = false;
        if ($this->save($data)) {
            return $this->id;
        }
        return false;
    }
    public function __construct($id = false, $table = null, $ds = null)
    {
        parent::__construct($id, $table, $ds);
        $this->validate = array(
            'title' => array(
                'rule1' => array(
                    'rule' => 'notempty',
                    'message' => __l('Required') ,
                ) ,
            ) ,
            'slug' => array(
                'rule2' => array(
                    'rule' => 'isUnique',
                    'message' => __l('Already exists') ,
                ) ,
                'rule1' => array(
                    'rule' => 'notempty',
                    'message' => __l('Required') ,
                ) ,
            ) ,
        );
    }
}
