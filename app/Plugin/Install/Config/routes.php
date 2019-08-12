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
$path = '/';
$url = array(
    'plugin' => 'install',
    'controller' => 'install'
);
if (file_exists(APP . 'Config' . DS . 'settings.yml')) {
    if (!Configure::read('Install.secured')) {
        $path = '/*';
        $url['action'] = 'finish';
    }
}
CmsRouter::connect($path, $url);
