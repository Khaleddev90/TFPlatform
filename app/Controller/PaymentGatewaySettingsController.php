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
class PaymentGatewaySettingsController extends AppController
{
    public $name = 'PaymentGatewaySettings';
    function admin_add($payment_gateway_id = null)
    {
        $this->pageTitle = __l('Add Payment Gateway Setting');
        if (!empty($this->request->data)) {
            $this->PaymentGatewaySetting->create();
            if ($this->PaymentGatewaySetting->save($this->request->data)) {
                $this->Session->setFlash(__l('Payment Gateway Setting has been added') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'edit',
                    $this->request->data['PaymentGatewaySetting']['payment_gateway_id']
                ));
            } else {
                $this->Session->setFlash(__l('Payment Gateway Setting could not be added. Please, try again.') , 'default', null, 'error');
            }
        } else {
            if (!is_null($payment_gateway_id)) {
                $this->request->data['PaymentGatewaySetting']['payment_gateway_id'] = $payment_gateway_id;
            }
        }
        $paymentGateways = $this->PaymentGatewaySetting->PaymentGateway->find('list');
        $this->set(compact('paymentGateways'));
    }
    function admin_edit($payment_gateway_id = null)
    {
        $this->pageTitle = __l('Edit Payment Gateway Setting');
        if (is_null($payment_gateway_id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $paymentGateway = $this->PaymentGatewaySetting->PaymentGateway->find('first', array(
            'fields' => array(
                'id',
                'name'
            ) ,
            'conditions' => array(
                'PaymentGateway.id' => $payment_gateway_id
            ) ,
            'recursive' => -1
        ));
        $this->set('payment_gateway_id', $payment_gateway_id);
        $paymentGatewaySettings = $this->PaymentGatewaySetting->find('all', array(
            'conditions' => array(
                'PaymentGatewaySetting.payment_gateway_id = ' => $payment_gateway_id
            ) ,
            'id' => array(
                'order ASC'
            ) ,
            'recursive' => 0
        ));
        $this->set(compact('paymentGatewaySettings', 'paymentGateway'));
    }
    /**
     * @TODO: Add some validation
     *
     */
    function admin_update()
    {
        // Save settings
        foreach($this->request->data['PaymentGatewaySetting'] as $id => $value) {
            if ($id != 'payment_gateway_id') {
                $this->PaymentGatewaySetting->create(array(
                    'id' => $id,
                    'value' => $value['key']
                ));
                $this->PaymentGatewaySetting->save(null, array(
                    'validate' => false
                ));
            }
        }
        $this->Session->setFlash(__l('Payment gateway settings updated.') , 'default', null, 'success');
        $this->redirect(array(
            'controller' => 'payment_gateway_settings',
            'action' => 'edit',
            $this->request->data['PaymentGatewaySetting']['payment_gateway_id']
        ));
    }
    function admin_delete($id = null)
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->PaymentGatewaySetting->delete($id)) {
            $this->Session->setFlash(__l('Payment Gateway Setting deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
}
?>