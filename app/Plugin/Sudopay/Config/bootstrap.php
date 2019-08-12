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
require_once 'constants.php';
CmsHook::setExceptionUrl(array(
    'sudopays/cancel_payment',
    'sudopays/success_payment',
    'sudopays/process_payment',
    'sudopays/process_ipn',
    'sudopays/update_account',
	'sudopays/confirmation'
));
$pluginModel = array();
if (isPluginEnabled('Jobs')) {
    $pluginModel = array(
        'JobUser' => array(
            'belongsTo' => array(
                'SudopayPaymentGateway' => array(
                    'className' => 'Sudopay.SudopayPaymentGateway',
                    'foreignKey' => 'sudopay_gateway_id',
                    'conditions' => '',
                    'fields' => '',
                    'order' => '',
                    'counterCache' => false
                ) ,
            ) ,
        ) ,
    );
}
CmsHook::bindModel($pluginModel);
?>