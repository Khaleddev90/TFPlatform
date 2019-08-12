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
class SocialContactsController extends AppController
{
    public $name = 'SocialContacts';
    public function index()
    {
        $this->pageTitle = __l('My Contacts');
        $this->loadModel('User');
        $type = $this->request->params['named']['type'];
        if ($type == 'gmail') {
            $type = 'google';
        }
        $conditions['SocialContact.user_id'] = $this->Auth->user('id');
        $conditions['SocialContact.social_source_id'] = constant(sprintf('%s::%s', 'ConstSocialSource', $type));
        $socialContacts = $this->SocialContact->find('all', array(
            'conditions' => $conditions,
            'contain' => array(
                'SocialContactDetail'
            ) ,
            'recursive' => 0
        ));
        if (!empty($socialContacts)) {
            foreach($socialContacts as $socialContact) {
                $contacts[] = $socialContact['SocialContactDetail']['email'];
            }
            $user_conditions['User.email'] = $contacts;
            $users = $this->User->find('list', array(
                'conditions' => $user_conditions,
                'fields' => array(
                    'User.id',
                    'User.email',
                ) ,
                'recursive' => - 1
            ));
            if (!empty($users)) {
                $conditions['SocialContactDetail.email'] = array_values($users);
            }
            $this->paginate = array(
                'conditions' => $conditions,
                'contain' => array(
                    'SocialContactDetail'
                ) ,
                'limit' => 15,
                'recursive' => 0
            );
            $this->set('inviteUsers', $this->paginate());
        }
    }
    public function delete($id = null)
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->SocialContact->delete($id)) {
            $type = $this->params['named']['type'];
            $foreign_id = $this->params['named']['foreign_id'];
            $import_action = $this->params['named']['import_action'];
            $this->Session->setFlash(sprintf(__l('%s deleted') , __l('User Contact')) , 'default', null, 'success');
            $this->redirect(array(
                'controller' => 'social_marketings',
                'action' => 'publish',
                $foreign_id,
                'type' => $type,
                'publish_action' => $import_action,
                'request' => true
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
    public function update()
    {
        $r = $this->request->data[$this->modelClass]['r'];
        if (!empty($this->request->data['SocialContact'])) {
            unset($this->request->data[$this->modelClass]['r']);
            foreach($this->request->data['SocialContact'] as $contact_id => $is_checked) {
                if ($is_checked['id']) {
                    $contactIds[] = $contact_id;
                }
            }
            if (!empty($contactIds)) {
                $contacts = $this->SocialContact->find('all', array(
                    'conditions' => array(
                        'SocialContact.id' => $contactIds
                    ) ,
                    'recursive' => 0
                ));
                if (!empty($contacts)) {
                    foreach($contacts as $contact) {
                        $emailFindReplace = array(
                            '##USERNAME##' => (!empty($contact['SocialContactDetail']['name'])) ? $contact['SocialContactDetail']['name'] : $contact['SocialContactDetail']['email'],
                            '##OTHER_USER_NAME##' => $this->Auth->user('username') ,
                            '##INVITE_LINK##' => Router::url('/', true) . 'r:' . $this->Auth->user('username') ,
                        );
                        App::import('Model', 'EmailTemplate');
                        $this->EmailTemplate = new EmailTemplate();
                        $email_template = $this->EmailTemplate->selectTemplate('Invite New User');
                        $this->SocialContact->_sendEmail($email_template, $emailFindReplace, $contact['SocialContactDetail']['email']);
                    }
                    $this->Session->setFlash(__l('Invite mail has been sent to selected contacts.') , 'default', null, 'success');
                } else {
                    $this->Session->setFlash(__l('Please select atleast one contact to invite.') , 'default', null, 'error');
                }
            } else {
                $this->Session->setFlash(__l('Please select atleast one contact to invite.') , 'default', null, 'error');
            }
        }
        $this->redirect($r);
    }
}
?>