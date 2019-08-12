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
class AclLinksRole extends AclAppModel
{
    public $name = 'AclLinksRole';
    //The Associations below have been created with all possible keys, those that are not needed can be removed
    public $belongsTo = array(
        'Role' => array(
            'className' => 'Acl.Role',
            'foreignKey' => 'role_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ) ,
        'AclLink' => array(
            'className' => 'Acl.AclLink',
            'foreignKey' => 'acl_link_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ) ,
        'AclLinkStatus' => array(
            'className' => 'Acl.AclLinkStatus',
            'foreignKey' => 'acl_link_status_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        )
    );
    public function __construct($id = false, $table = null, $ds = null) 
    {
        parent::__construct($id, $table, $ds);
    }
}
?>