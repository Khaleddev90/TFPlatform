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
// to show add fund link in users action
Cms::hookAdminRowAction('Users/admin_index', '<i class="icon-plus"></i> Add Fund', 'controller:user_add_wallet_amounts/action:add_fund/:id', array(
    'title' => 'Add Fund',
    'escape' => false,
));
// to show deduct fund link in users action
Cms::hookAdminRowAction('Users/admin_index', '<i class="icon-minus"></i> Deduct Fund', 'controller:user_add_wallet_amounts/action:deduct_fund/:id', array(
    'title' => __l('Deduct Fund'),
	'escape' => false,
));
CmsHook::bindModel(array(
    'User' => array(
        'hasMany' => array(
            'UserAddWalletAmount' => array(
                'className' => 'Wallets.UserAddWalletAmount',
                'foreignKey' => 'user_id',
                'dependent' => true,
                'conditions' => '',
                'fields' => '',
                'order' => '',
                'limit' => '',
                'offset' => '',
                'exclusive' => '',
                'finderQuery' => '',
                'counterQuery' => ''
            )
        )
    )
));
?>