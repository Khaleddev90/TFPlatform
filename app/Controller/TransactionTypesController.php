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
class TransactionTypesController extends AppController
{
    public $name = 'TransactionTypes';
    function admin_index()
    {
        $this->pageTitle = __l('Transaction Types');
        $this->TransactionType->recursive = -1;
        $this->set('transactionTypes', $this->paginate());
    }
    function admin_edit($id = null)
    {
        $this->pageTitle = __l('Edit Transaction Type');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if (!empty($this->request->data)) {
            if ($this->TransactionType->save($this->request->data)) {
                $this->Session->setFlash(__l('Transaction Type has been updated') , 'default', null, 'success');
                $this->redirect(array(
                    'controller' => 'transaction_types',
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l('Transaction Type could not be updated. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->request->data = $this->TransactionType->read(null, $id);
            if (empty($this->request->data)) {
                throw new NotFoundException(__l('Invalid request'));
            }
        }
        $this->pageTitle.= ' - ' . $this->request->data['TransactionType']['name'];
    }
}
?>