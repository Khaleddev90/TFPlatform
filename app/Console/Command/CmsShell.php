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
App::uses('Security', 'Utility');
class CmsShell extends AppShell
{
    public function getOptionParser() 
    {
        $parser = parent::getOptionParser();
        $parser->description(__l('Cms Utilities'))->addSubcommand('password', array(
            'help' => 'Get hashed password',
            'parser' => array(
                'description' => 'Get hashed password',
                'arguments' => array(
                    'password' => array(
                        'required' => true,
                        'help' => 'Password to hash',
                    ) ,
                ) ,
            ) ,
        ));
        return $parser;
    }
    /**
     * Get hashed password
     *
     * Usage: ./cake cms password myPasswordHere
     */
    public function password() 
    {
        $value = trim($this->args['0']);
        $this->out(Security::hash($value, null, true));
    }
    /**
     * Prepares data in config/schema/data/ required for install plugin
     *
     * Usage: ./cake cms data table_name_here
     */
    public function data() 
    {
        $connection = 'default';
        $records = array();
        App::import('Model', 'CakeSchema', false);
        $schema = &new CakeSchema(array(
            'name' => 'app',
            'file' => $this->args['1'] . '_schema.php',
        ));
        $schema = $schema->load();
        foreach($schema->tables as $table => $fields) {
            // get records
            $modelAlias = Inflector::camelize(Inflector::singularize($table));
            App::import('Model', 'Model', false);
            $model = &new Model(array(
                'name' => $modelAlias,
                'table' => $table,
                'ds' => $connection
            ));
            $records = $model->find('all', array(
                'recursive' => -1,
            ));
            // generate file content
            $recordString = '';
            foreach($records as $record) {
                $values = array();
                foreach($record[$modelAlias] as $field => $value) {
                    $value = addslashes($value);
                    $values[] = "\t\t\t'$field' => '$value'";
                }
                $recordString.= "\t\tarray(";
                $recordString.= implode(",", $values);
                $recordString.= "\t\t),";
            }
            $className = $modelAlias . 'Data';
            $content = "<?php
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
 */";
            $content.= "class " . $modelAlias . "Data" . " {";
            $content.= "\tpublic \$table = '" . $table . "';";
            $content.= "\tpublic \$records = array(";
            $content.= $recordString;
            $content.= "\t);";
            $content.= "}";
            // write file
            $filePath = APP . 'Plugin' . DS . 'Install' . DS . 'Config' . DS . ucfirst($this->args['1']) . 'Data' . DS . Inflector::camelize($modelAlias) . 'Data.php';
            if (!file_exists($filePath)) {
                touch($filePath);
            }
            App::uses('File', 'Utility');
            $file = new File($filePath, true);
            $file->write($content);
            $this->out('New file generated: ' . $filePath);
        }
    }
}
