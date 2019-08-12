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
class SocialContactDetail extends AppModel
{
    public $name = 'SocialContactDetail';
    public $hasMany = array(
        'SocialContact' => array(
            'className' => 'SocialMarketing.SocialContact',
            'foreignKey' => 'social_contact_detail_id',
            'dependent' => true,
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        )
    );
}
?>