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
class AffiliateTypeData
{
    public $table = 'affiliate_types';
    public $records = array(
        array(
            'id' => '1',
            'created' => '2011-02-08 00:00:00',
            'modified' => '2013-12-06 11:19:39',
            'name' => 'Sign Up',
            'model_name' => 'User',
            'commission' => '0.00',
            'affiliate_commission_type_id' => '2',
            'is_active' => '1'
        ) ,
        array(
            'id' => '2',
            'created' => '2011-02-08 00:00:00',
            'modified' => '2013-12-06 11:19:39',
            'name' => 'Purchase',
            'model_name' => 'JobOrder',
            'commission' => '2.00',
            'affiliate_commission_type_id' => '1',
            'is_active' => '1'
        ) ,
    );
}
