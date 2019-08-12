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
class Meta extends AppModel
{
    /**
     * Model name
     *
     * @var string
     * @access public
     */
    public $name = 'Meta';
    /**
     * Table name
     *
     * @var string
     * @access public
     */
    public $useTable = 'meta';
    /**
     * Model associations: belongsTo
     *
     * @var array
     * @access public
     */
    public $belongsTo = array(
        'Node' => array(
            'className' => 'Node',
            'foreignKey' => 'foreign_key',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ) ,
    );
}
