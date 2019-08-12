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
class JobServiceLocationData
{
    public $table = 'job_service_locations';
    public $records = array(
        array(
            'id' => '1',
            'name' => 'Buyer',
            'job_count' => '0',
            'description' => 'Service in seller location. Buyer needs to visit seller location for his/her job.'
        ) ,
        array(
            'id' => '2',
            'name' => 'Seller',
            'job_count' => '0',
            'description' => 'Service in buyer location. Seller needs to visit buyer location for his/her job.'
        ) ,
    );
}
