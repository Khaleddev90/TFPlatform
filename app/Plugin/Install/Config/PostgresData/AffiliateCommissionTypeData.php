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
class AffiliateCommissionTypeData {

	public $table = 'affiliate_commission_types';

	public $records = array(
		array(
			'id' => '1',
			'name' => '%',
			'description' => 'Percentage'
		),
		array(
			'id' => '2',
			'name' => '$',
			'description' => 'Amount'
		),
	);

}
