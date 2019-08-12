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
class AffiliateCashWithdrawal extends AppModel
{
    public $name = 'AffiliateCashWithdrawal';
    //$validate set in __construct for multi-language support
    //The Associations below have been created with all possible keys, those that are not needed can be removed
    public $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ) ,
        'AffiliateCashWithdrawalStatus' => array(
            'className' => 'Affiliates.AffiliateCashWithdrawalStatus',
            'foreignKey' => 'affiliate_cash_withdrawal_status_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ) ,
        'PaymentGateway' => array(
            'className' => 'PaymentGateway',
            'foreignKey' => 'payment_gateway_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        )
    );
    function __construct($id = false, $table = null, $ds = null)
    {
        parent::__construct($id, $table, $ds);
        $this->validate = array(
            'user_id' => array(
                'rule' => 'numeric',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'amount' => array(
                'rule2' => array(
                    'rule' => 'numeric',
                    'message' => __l('Should be numeric')
                ) ,
                'rule1' => array(
                    'rule' => 'notempty',
                    'message' => __l('Required')
                )
            ) ,
            'description' => array(
                'rule' => 'notempty',
                'allowEmpty' => false,
                'message' => __l('Required')
            )
        );
        $this->moreActions = array(
            ConstAffiliateCashWithdrawalStatus::Pending => __l('Pending') ,
            ConstAffiliateCashWithdrawalStatus::Approved => __l('Approved') ,
            ConstAffiliateCashWithdrawalStatus::Rejected => __l('Rejected')
        );
    }
    function _checkAmount($amount)
    {
        $user = $this->User->find('first', array(
            'conditions' => array(
                'User.id' => $this->data[$this->name]['user_id']
            ) ,
            'fields' => array(
                'User.commission_line_amount',
                'User.role_id',
            ) ,
            'recursive' => -1
        ));
        $user_available_balance = $user['User']['commission_line_amount'];
        if ($user_available_balance < $amount) {
            return __l('Given amount is greater than your commission amount');
        }
        if ($user['User']['role_id'] == ConstUserTypes::User) {
            if ($amount < Configure::read('affiliate.payment_threshold_for_threshold_limit_reach')) {
                return __l('Given amount is less than withdraw limit');
            }
        }
        return true;
    }
    function affiliate_masspay_ipn_process($userCashWithdrawal_id, $userCashWithdrawal_response, $gateway_id = ConstPaymentGateways::SudoPay, $logTable = 'SudopayTransactionLog')
    {
        $userCashWithdrawal = $this->find('first', array(
            'conditions' => array(
                'AffiliateCashWithdrawal.id' => $userCashWithdrawal_id,
                'AffiliateCashWithdrawal.affiliate_cash_withdrawal_status_id' => ConstAffiliateCashWithdrawalStatus::Approved,
            ) ,
            'contain' => array(
                'User',
                $logTable => array(
                    'fields' => array(
                        $logTable . '.id',
                        $logTable . '.user_id',
                        $logTable . '.transaction_id',
                        $logTable . '.affiliate_cash_withdrawal_id',
                        $logTable . '.orginal_amount',
                        $logTable . '.rate',
                        $logTable . '.masspay_response',
                    )
                ) ,
            ) ,
            'recursive' => 1
        ));
        $return = '';
        if (!empty($userCashWithdrawal)) {
            if (!empty($userCashWithdrawal)) {
                if ($userCashWithdrawal_response['status'] == 'Completed') {
                    $transaction_id = $this->onSuccessProcess($userCashWithdrawal, $userCashWithdrawal_response, $logTableData);
                } else {
                    $transaction_id = $this->onFailedProcess($userCashWithdrawal);
                }
                $return['transaction_id'] = $transaction_id;
                $return['log_id'] = $userCashWithdrawal[$logTable]['id'];
            }
            return $return;
        }
    }
    public function onSuccessProcess($affiliateCashWithdrawal, $affiliateCashWithdrawal_response = array() , $logTable = array())
    {
        $transaction_id = $this->User->Transaction->log_data($affiliateCashWithdrawal['AffiliateCashWithdrawal']['id'], 'Affiliates.AffiliateCashWithdrawal', $affiliateCashWithdrawal['AffiliateCashWithdrawal']['payment_gateway_id'], ConstTransactionTypes::AffiliateCashWithdrawalRequestPaid);
        $this->User->updateAll(array(
            'User.commission_paid_amount' => 'User.commission_paid_amount +' . $affiliateCashWithdrawal['AffiliateCashWithdrawal']['amount']
        ) , array(
            'User.id' => $affiliateCashWithdrawal['AffiliateCashWithdrawal']['user_id']
        ));
        $this->updateAll(array(
            'AffiliateCashWithdrawal.affiliate_cash_withdrawal_status_id' => ConstAffiliateCashWithdrawalStatus::Success
        ) , array(
            'AffiliateCashWithdrawal.id' => $affiliateCashWithdrawal['AffiliateCashWithdrawal']['id']
        ));
        $this->User->updateAll(array(
            'User.commission_withdraw_request_amount' => 'User.commission_withdraw_request_amount -' . $affiliateCashWithdrawal['AffiliateCashWithdrawal']['amount']
        ) , array(
            'User.id' => $affiliateCashWithdrawal['AffiliateCashWithdrawal']['user_id']
        ));
        return $transaction_id;
    }
    public function onFailedProcess($affiliateCashWithdrawal)
    {
        $transaction_id = $this->User->Transaction->log_data($affiliateCashWithdrawal['AffiliateCashWithdrawal']['id'], 'Affiliates.AffiliateCashWithdrawal', $affiliateCashWithdrawal['AffiliateCashWithdrawal']['payment_gateway_id'], ConstTransactionTypes::AffiliateCashWithdrawalRequestFailed);
        $this->User->updateAll(array(
            'User.commission_line_amount' => 'User.commission_line_amount +' . $affiliateCashWithdrawal['AffiliateCashWithdrawal']['amount']
        ) , array(
            'User.id' => $affiliateCashWithdrawal['AffiliateCashWithdrawal']['user_id']
        ));
        $this->User->updateAll(array(
            'User.commission_withdraw_request_amount' => 'User.commission_withdraw_request_amount -' . $affiliateCashWithdrawal['AffiliateCashWithdrawal']['amount']
        ) , array(
            'User.id' => $affiliateCashWithdrawal['AffiliateCashWithdrawal']['user_id']
        ));
        return $transaction_id;
    }
    public function onApprovedProcess($userCashWithdrawalIds, $status = array() , $logTable = 'SudopayTransactionLog')
    {
        APP::Import('Model', $logTable);
        $this->
        {
            $logTable} = new $logTable();
            foreach($userCashWithdrawalIds as $userCashWithdrawalId) {
                $cash_withdraw = $this->find('first', array(
                    'conditions' => array(
                        'AffiliateCashWithdrawal.id' => $userCashWithdrawalId
                    ) ,
                    'recursive' => -1
                ));
                if (!empty($userCashWithdrawalId) && !empty($cash_withdraw)) {
                    $transaction_id = $this->User->Transaction->log_data($cash_withdraw['AffiliateCashWithdrawal']['id'], 'Affiliates.AffiliateCashWithdrawal', ConstPaymentGateways::SudoPay, ConstTransactionTypes::AffiliateCashWithdrawalRequestApproved);
                    // update log transaction id
                    if (!empty($status)) {
                        $log_array = array();
                        $log_array[$logTable]['id'] = $status['log_list'][$userCashWithdrawalId];
                        $log_array[$logTable]['transaction_id'] = $transaction_id;
                        $this->$logTable->save($log_array);
                    }
                    // update status
                    $user_cash_data = array();
                    $user_cash_data['AffiliateCashWithdrawal']['id'] = $userCashWithdrawalId;
                    $user_cash_data['AffiliateCashWithdrawal']['payment_gateway_id'] = ConstPaymentGateways::SudoPay;
                    $user_cash_data['AffiliateCashWithdrawal']['affiliate_cash_withdrawal_status_id'] = ConstAffiliateCashWithdrawalStatus::Approved;
                    $this->save($user_cash_data);
                }
            }
        }
    }
?>