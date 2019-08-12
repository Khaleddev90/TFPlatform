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
CmsNav::add('analytics', array(
    'title' => __l('Analytics') ,
    'icon-class' => 'bar-chart',
	'weight' => 30,
    'children' => array(
        'google_analytics' => array(
            'title' => __l('Google Analytics') ,
            'url' => array(
                'admin' => true,
                'controller' => 'google_analytics',
                'action' => 'analytics_chart',
            ) ,
			'htmlAttributes' => array(
                'class' => 'js-no-pjax'
            ) ,
            'weight' => 10,
        ) ,
    )
));
CmsHook::setJsFile(array(
    APP . 'Plugin' . DS . 'IntegratedGoogleAnalytics' . DS . 'webroot' . DS . 'js' . DS . 'common.js'
) , 'default');
?>