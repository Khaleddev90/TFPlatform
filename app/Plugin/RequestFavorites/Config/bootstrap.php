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
CmsNav::add('activities', array(
    'title' => __l('Activities') ,
    'weight' => 50,
	'children' => array(
        'Request Favorites' => array(
            'title' => sprintf(__l('%s Favorites'), requestAlternateName(ConstRequestAlternateName::Singular,ConstRequestAlternateName::FirstLeterCaps)) ,
            'url' => array(
                'controller' => 'request_favorites',
                'action' => 'index',
            ) ,
            'weight' => 80,
        )
    ) ,
));
$defaultModel = array(
	'User' => array(
		'hasMany' => array(
			'RequestFavorite' => array(
				'className' => 'RequestFavorites.RequestFavorite',
				'foreignKey' => 'user_id',
				'dependent' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => '',
			) ,
		),
	),
);
if (isPluginEnabled('Requests')) {
    $pluginModel = array(
        'Request' => array(
            'hasMany' => array(
                'RequestFavorite' => array(
					'className' => 'RequestFavorites.RequestFavorite',
					'foreignKey' => 'request_id',
					'conditions' => '',
					'fields' => '',
					'order' => '',
					'counterCache' => true
				),
            ) ,			
        ) ,
    );
    $defaultModel = $defaultModel + $pluginModel;
}
CmsHook::bindModel($defaultModel);