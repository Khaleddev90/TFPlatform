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
class SocialContact extends AppModel
{
    public $name = 'SocialContact';
    public $belongsTo = array(
        'SocialContactDetail' => array(
            'className' => 'SocialMarketing.SocialContactDetail',
            'foreignKey' => 'social_contact_detail_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'counterCache' => true,
        ) ,
    );
    function __construct($id = false, $table = null, $ds = null) 
    {
        parent::__construct($id, $table, $ds);
    }
}
?>