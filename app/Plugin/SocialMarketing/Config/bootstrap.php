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
if (!empty($_REQUEST['request_ids'])) {
    Cms::hookComponent('*', 'SocialMarketing.FacebookRequest');
}
