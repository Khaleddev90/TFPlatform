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
class WithdrawalStatusData
{
    public $table = 'withdrawal_statuses';
    public $records = array(
        array(
            'id' => '1',
            'created' => '0000-00-00 00:00:00',
            'modified' => '0000-00-00 00:00:00',
            'name' => 'Pending',
            'user_cash_withdrawal_count' => '0'
        ) ,
        array(
            'id' => '2',
            'created' => '0000-00-00 00:00:00',
            'modified' => '0000-00-00 00:00:00',
            'name' => 'Accepted',
            'user_cash_withdrawal_count' => '0'
        ) ,
        array(
            'id' => '3',
            'created' => '0000-00-00 00:00:00',
            'modified' => '0000-00-00 00:00:00',
            'name' => 'Rejected',
            'user_cash_withdrawal_count' => '0'
        ) ,
        array(
            'id' => '4',
            'created' => '0000-00-00 00:00:00',
            'modified' => '0000-00-00 00:00:00',
            'name' => 'Failed',
            'user_cash_withdrawal_count' => '0'
        ) ,
        array(
            'id' => '5',
            'created' => '0000-00-00 00:00:00',
            'modified' => '0000-00-00 00:00:00',
            'name' => 'Success',
            'user_cash_withdrawal_count' => '0'
        ) ,
    );
}
