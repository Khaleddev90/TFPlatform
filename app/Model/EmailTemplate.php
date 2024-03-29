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
class EmailTemplate extends AppModel
{
    public $name = 'EmailTemplate';
    public $displayField = 'name';
    function __construct($id = false, $table = null, $ds = null)
    {
        parent::__construct($id, $table, $ds);
        $this->validate = array(
            'from' => array(
                'rule' => 'notempty',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'subject' => array(
                'rule' => 'notempty',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'email_text_content' => array(
                'rule' => 'notempty',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'email_html_content' => array(
                'rule' => 'notempty',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
        );
    }
    function selectTemplate($tempName)
    {
        $emailTemplate = $this->find('first', array(
            'conditions' => array(
                'EmailTemplate.name' => $tempName
            ) ,
            'fields' => array(
                'EmailTemplate.email_text_content',
                'EmailTemplate.email_html_content',
                'EmailTemplate.subject',
                'EmailTemplate.from',
                'EmailTemplate.reply_to'
            ) ,
            'recursive' => -1
        ));
        $resultArray = array();
        foreach($emailTemplate as $singleArrayEmailTemplate) {
            foreach($singleArrayEmailTemplate as $key => $value) {
                $resultArray[$key] = $value;
            }
        }
        return $resultArray;
    }
}
?>