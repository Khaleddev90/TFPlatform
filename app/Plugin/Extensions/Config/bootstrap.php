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
CmsNav::add('plugins', array(
    'title' => __l('Plugins') ,
    'weight' => 70,
    'data-bootstro-step' => '7',
    'data-bootstro-content' => __l('To manage all plugins and their settings.') ,
    'icon-class' => 'certificate',
    'children' => array(
        'plugins' => array(
            'title' => __l('Plugins') ,
            'url' => array(
                'controller' => 'extensions_plugins',
                'action' => 'index',
            ) ,
            'htmlAttributes' => array(
                'class' => 'separator',
            ) ,
            'weight' => 70,
        ) ,
    ) ,
));
