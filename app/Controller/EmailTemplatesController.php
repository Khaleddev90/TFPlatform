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
class EmailTemplatesController extends AppController
{
    public $name = 'EmailTemplates';
    function admin_index()
    {
        $this->pageTitle = __l('Email Templates');
        $this->EmailTemplate->recursive = -1;
        $this->paginate = array(
            'limit' => 50
        );
        $this->set('emailTemplates', $this->paginate());
    }
    function admin_edit($id = null)
    {
        $this->disableCache();
        $this->pageTitle = __l('Edit Email Template');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if (!empty($this->request->data)) {
            if (Configure::read('site.is_admin_settings_enabled')) {
                if ($this->EmailTemplate->save($this->request->data)) {
                    $this->Session->setFlash(__l('Email Template has been updated') , 'default', null, 'success');
                } else {
                    $this->Session->setFlash(__l('Email Template could not be updated. Please, try again.') , 'default', null, 'error');
                }
                $emailTemplate = $this->EmailTemplate->find('first', array(
                    'conditions' => array(
                        'EmailTemplate.id' => $this->request->data['EmailTemplate']['id']
                    ) ,
                    'fields' => array(
                        'EmailTemplate.name',
                        'EmailTemplate.email_variables'
                    ) ,
                    'recursive' => -1
                ));
                $this->request->data['EmailTemplate']['name'] = $emailTemplate['EmailTemplate']['name'];
                $this->request->data['EmailTemplate']['email_variables'] = $emailTemplate['EmailTemplate']['email_variables'];
            } else {
                $this->Session->setFlash(__l('Sorry. You Cannot Update the Settings in Demo Mode') , 'default', null, 'error');
            }
        } else {
            $this->request->data = $this->EmailTemplate->read(null, $id);
            if (empty($this->request->data)) {
                throw new NotFoundException(__l('Invalid request'));
            }
        }
        $this->pageTitle.= ' - ' . $this->request->data['EmailTemplate']['name'];
        $this->set('email_variables', explode(',', $this->request->data['EmailTemplate']['email_variables']));
    }
}
?>