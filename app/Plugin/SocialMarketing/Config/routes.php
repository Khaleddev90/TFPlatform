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
CmsRouter::connect('/social_marketings/import_friends/type/:type', array(
    'controller' => 'social_marketings',
    'action' => 'import_friends',
));