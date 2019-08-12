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
CmsNav::add('masters', array(
    'title' => 'Masters',
    'weight' => 100,
	'children' => array(
        'Account' => array(
            'title' => __l('Account') ,
            'url' => '',
            'weight' => 500,
        ) ,
        'Security Questions' => array(
            'title' => __l('Security Questions') ,
            'url' => array(
                'controller' => 'security_questions',
                'action' => 'index'
            ) ,
            'weight' => 510,
        ) ,
    )
));
$defaultModel = array(
    'User' => array(
        'belongsTo' => array(
            'SecurityQuestion' => array(
                'className' => 'SecurityQuestions.SecurityQuestion',
                'foreignKey' => 'security_question_id',
                'conditions' => '',
                'fields' => '',
                'order' => ''
            ) ,
        ) ,
    ) ,
);
CmsHook::bindModel($defaultModel);