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
class DisputeTypeData
{
    public $table = 'dispute_types';
    public $records = array(
        array(
            'id' => '1',
            'created' => '2010-12-22 10:46:41',
            'modified' => '2010-12-22 10:46:41',
            'name' => 'I received an item that does not match the seller\'s description (Only for Online Jobs)',
            'job_user_type_id' => '1',
            'is_active' => '1'
        ) ,
        array(
            'id' => '2',
            'created' => '2010-12-22 10:46:41',
            'modified' => '2010-12-22 10:46:41',
            'name' => 'Buyer requesting rework without reason (Only for Online Jobs)',
            'job_user_type_id' => '2',
            'is_active' => '1'
        ) ,
        array(
            'id' => '3',
            'created' => '2010-12-22 10:46:41',
            'modified' => '2010-12-22 10:46:41',
            'name' => 'Buyer given poor feedback',
            'job_user_type_id' => '2',
            'is_active' => '1'
        ) ,
    );
}
