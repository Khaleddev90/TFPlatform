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
class SocialMarketing extends AppModel
{
    public $name = 'SocialMarketing';
    var $useTable = false;
    public function import_contacts($social_contacts, $social_type) 
    {
        if (!empty($social_contacts)) {
            App::import('Model', 'SocialMarketing.SocialContactDetail');
            $this->SocialContactDetail = new SocialContactDetail();
            App::import('Model', 'SocialMarketing.SocialContact');
            $this->SocialContact = new SocialContact();
            foreach($social_contacts as $contact) {
                $contact_detail_id = 0;
                $identifier = $contact->identifier;
                $email = $contact->email;
                $name = $contact->displayName;
                if ($social_type == 'fb') {
                    $social_type = 'facebook';
                }
                $source_id = constant(sprintf('ConstSocialSource::%s', $social_type));
                if (in_array($source_id, array(
                    ConstSocialSource::facebook,
                    ConstSocialSource::twitter,
                    ConstSocialSource::googleplus,
                    ConstSocialSource::linkedin
                ))) {
                    $condition['SocialContactDetail.' . $social_type . '_user_id'] = $identifier;
                } else {
                    $condition['SocialContactDetail.email'] = $email;
                }
                $findExist = $this->SocialContactDetail->find('first', array(
                    'conditions' => $condition,
                    'recursive' => 0
                ));
                if (!empty($findExist)) {
                    $contact_detail_id = $findExist['SocialContactDetail']['id'];
                }
                $findExistContact = $this->SocialContact->find('count', array(
                    'conditions' => array(
                        'SocialContact.user_id' => $_SESSION['Auth']['User']['id'],
                        'SocialContact.social_contact_detail_id' => $findExist['SocialContactDetail']['id'],
                    ) ,
                    'recursive' => -1
                ));
                if (empty($findExistContact)) {
                    if (empty($contact_detail_id)) {
                        $_Data = array();
                        $_Data['SocialContactDetail']['email'] = $email;
                        $_Data['SocialContactDetail']['name'] = $name;
                        $_Data['SocialContactDetail'][$social_type . '_user_id'] = $identifier;
                        $this->SocialContactDetail->create();
                        $this->SocialContactDetail->save($_Data['SocialContactDetail']);
                        $contact_detail_id = $this->SocialContactDetail->id;
                    }
                    $_data = array();
                    $_data['SocialContact']['user_id'] = $_SESSION['Auth']['User']['id'];
                    $_data['SocialContact']['social_source_id'] = $source_id;
                    $_data['SocialContact']['social_contact_detail_id'] = $contact_detail_id;
                    $this->SocialContact->create();
                    $this->SocialContact->save($_data['SocialContact']);
                }
            }
            $this->updateCountInUser();
        }
    }
    public function updateCountInUser() 
    {
        App::import('Model', 'SocialMarketing.SocialContact');
        $this->SocialContact = new SocialContact();
        App::import('Model', 'User');
        $this->User = new User();
        $social_sources = array(
            ConstSocialSource::facebook,
            ConstSocialSource::twitter,
            ConstSocialSource::yahoo,
            ConstSocialSource::google,
            ConstSocialSource::googleplus,
            ConstSocialSource::linkedin
        );
        $user_data = array();
        foreach($social_sources as $social_source_id) {
            $contact_count = 0;
            $contact_count = $this->SocialContact->find('count', array(
                'conditions' => array(
                    'SocialContact.user_id' => $_SESSION['Auth']['User']['id'],
                    'SocialContact.social_source_id' => $social_source_id
                )
            ));
            if ($social_source_id == ConstSocialSource::facebook) {
                $user_data['User.fb_friends_count'] = $contact_count;
            }
            if ($social_source_id == ConstSocialSource::twitter) {
                $user_data['User.twitter_followers_count'] = $contact_count;
            }
            if ($social_source_id == ConstSocialSource::yahoo) {
                $user_data['User.yahoo_contacts_count'] = $contact_count;
            }
            if ($social_source_id == ConstSocialSource::google) {
                $user_data['User.google_contacts_count'] = $contact_count;
            }
            if ($social_source_id == ConstSocialSource::googleplus) {
                $user_data['User.googleplus_contacts_count'] = $contact_count;
            }
            if ($social_source_id == ConstSocialSource::linkedin) {
                $user_data['User.linkedin_contacts_count'] = $contact_count;
            }
        }
        $this->User->updateAll($user_data, array(
            'User.id' => $_SESSION['Auth']['User']['id']
        ));
    }
    public function getFacebookCount($url) 
    {
        $facebook_url = 'http://graph.facebook.com/?ids=' . $url;
        $count = 0;
        $data = $this->getCount($facebook_url);
        $json = json_decode($data, true);
        if (!empty($json[$url]['count'])) {
            $count = intval($json[$url]['count']);
        }
        return $count;
    }
    public function getFacebookFriendCount($user) 
    {
        $facebook_url = 'https://graph.facebook.com/me/friends?access_token=' . $user['User']['facebook_access_token'];
        $count = 0;
        $data = $this->getCount($facebook_url);
        $json = json_decode($data, true);
        if (!empty($json[$url]['count'])) {
            $count = intval($json[$url]['count']);
        }
        return $count;
    }
    public function getTwitterCount($url) 
    {
        $twitter_url = 'http://urls.api.twitter.com/1/urls/count.json?url=' . $url;
        $count = 0;
        $data = $this->getCount($twitter_url);
        $json = json_decode($data, true);
        if (!empty($json['count'])) {
            $count = intval($json['count']);
        }
        return $count;
    }
    public function getLinkedinCount($url) 
    {
        $linkedin_url = 'http://www.linkedin.com/countserv/count/share?url=' . $url . '&format=json';
        $count = 0;
        $data = $this->getCount($linkedin_url);
        $json = json_decode($data, true);
        if (!empty($json['count'])) {
            $count = intval($json['count']);
        }
        return $count;
    }
    public function getGmailCount($url) 
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "https://clients6.google.com/rpc");
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, '[{"method":"pos.plusones.get","id":"p","params":{"nolog":true,"id":"' . $url . '","source":"widget","userId":"@viewer","groupId":"@self"},"jsonrpc":"2.0","key":"p","apiVersion":"v1"}]');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Content-type: application/json'
        ));
        $curl_results = curl_exec($curl);
        curl_close($curl);
        $count = 0;
        if (!empty($curl_results)) {
            $json = json_decode($curl_results, true);
            $count = intval($json[0]['result']['metadata']['globalCounts']['count']);
        }
        return $count;
    }
    public function getCount($url) 
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //Set curl to return the data instead of printing it to the browser.
        curl_setopt($ch, CURLOPT_URL, $url);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
    public function updateSocialActivityCount() 
    {
        App::import('Model', 'Project');
        $this->Project = new Project();
        $conditions = array();
        $response = Cms::dispatchEvent('Controller.ProjectType.getConditions', $this, array(
            'page' => 'cron',
            'type' => 'city_count'
        ));
        if (!empty($response->data['conditions'])) {
            $conditions = array_merge($conditions, $response->data['conditions']);
        }
        $projects = $this->Project->find('all', array(
            'conditions' => $conditions
        ));
        foreach($projects as $project) {
            $_data['Project']['id'] = $project['Project']['id'];
            $url = Router::url(array(
                'controller' => 'projects',
                'action' => 'view',
                $project['Project']['slug'],
            ) , true);
            $_data['Project']['facebook_share_count'] = $this->getFacebookCount($url);
            $_data['Project']['twitter_share_count'] = $this->getTwitterCount($url);
            $_data['Project']['gmail_share_count'] = $this->getGmailCount($url);
            $_data['Project']['linkedin_share_count'] = $this->getLinkedinCount($url);
            $this->Project->save($_data);
        }
    }
}
?>