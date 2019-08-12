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
class ConstAffiliateCashWithdrawalStatus
{
    const Pending = 1;
    const Approved = 2;
    const Rejected = 3;
    const Failed = 4;
    const Success = 5;
}
class ConstAffiliateStatus
{
    const Pending = 1;
    const Canceled = 2;
    const PipeLine = 3;
    const Completed = 4;
    const Success = 5;
}
class ConstAffiliateCommissionType
{
    const Percentage = 1;
    const Amount = 2;
}
class ConstAffiliateRequests
{
    const Pending = 0;
    const Accepted = 1;
    const Rejected = 2;
}
?>
