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
class AffiliateTypesController extends AppController
{
    public $name = 'AffiliateTypes';
    function beforeFilter()
    {
        if (!isPluginEnabled('Affiliates') && $this->Auth->user('role_id') != ConstUserTypes::Admin) {
            throw new NotFoundException(__l('Invalid request'));
        }
        parent::beforeFilter();
    }
    function admin_index()
    {
        $this->pageTitle = __l('Affiliate Types');
        $this->AffiliateType->recursive = 0;
        $moreActions = $this->AffiliateType->moreActions;
        $this->set(compact('moreActions'));
        $this->set('affiliateTypes', $this->paginate());
    }
    function admin_edit($id = null)
    {
        $this->pageTitle = __l('Edit Affiliate Type');
        if (!empty($this->request->data)) {
            $i = 0;
            $errors = array();
            foreach($this->request->data['AffiliateType'] as $data) {
                $this->AffiliateType->set($data);
                if (!$this->AffiliateType->save($data)) {
                    $errors[$i] = $this->AffiliateType->validationErrors;
                }
                $i++;
            }
            foreach($errors as $i => $error) {
                foreach($error as $key => $value) $this->AffiliateType->validationErrors[$i][$key] = $value;
            }
            if (empty($errors)) {
                unlink(APP . '/tmp/cache/cake_affiliate_type_affiliate_model');
                $this->Session->setFlash(__l('Affiliate Type has been updated') , 'default', null, 'success');
            } else $this->Session->setFlash(__l(' Affiliate Type could not be updated. Please, try again.') , 'default', null, 'error');
        } else {
            $affiliateTypes = $this->AffiliateType->find('all', array(
                'recursive' => -1
            ));
            if (empty($affiliateTypes)) {
                throw new NotFoundException(__l('Invalid request'));
            }
            $i = 0;
            foreach($affiliateTypes as &$affiliateType) {
                foreach($affiliateType as $key => $value) $this->request->data['AffiliateType'][$i] = $value;
                $i++;
            }
        }
        $affiliateCommissionTypes = $this->AffiliateType->AffiliateCommissionType->find('list');
        $this->set(compact('affiliateCommissionTypes'));
    }
}
?>