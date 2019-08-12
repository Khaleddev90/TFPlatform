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
class DisputeClosedType extends AppModel
{
    public $name = 'DisputeClosedType';
    public $displayField = 'name';
    //The Associations below have been created with all possible keys, those that are not needed can be removed
    public $belongsTo = array(
        'DisputeType' => array(
            'className' => 'DisputeType',
            'foreignKey' => 'dispute_type_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ) ,
    );
    function __construct($id = false, $table = null, $ds = null)
    {
        parent::__construct($id, $table, $ds);
    }
    function getDisputeClosedType($close_type_id)
    {
        $get_close_type = $this->find('first', array(
            'conditions' => array(
                'DisputeClosedType.id' => $close_type_id
            ) ,
            'recursive' => -1
        ));
        if (!empty($get_close_type)) {
            return $get_close_type;
        } else {
            return '';
        }
    }
}
?>