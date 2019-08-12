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
require_once 'constants.php';
CmsHook::setCssFile(array(
    APP . 'Plugin' . DS . 'Acl' . DS . 'webroot' . DS . 'css' . DS . 'acl.css'
) , 'admin');
CmsHook::setJsFile(array(
    APP . 'Plugin' . DS . 'Acl' . DS . 'webroot' . DS . 'js' . DS . 'common.js'
) , 'default');
