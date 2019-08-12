<?php
/**
 * Dispatcher takes the URL information, parses it for parameters and
 * tells the involved controllers what to do.
 *
 * This is the heart of Cake's operation.
 *
 * PHP 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       Cake.Routing
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('Router', 'Routing');
App::uses('CakeRequest', 'Network');
App::uses('CakeResponse', 'Network');
App::uses('Controller', 'Controller');
App::uses('Scaffold', 'Controller');
App::uses('View', 'View');
App::uses('Debugger', 'Utility');
App::uses('CakeEvent', 'Event');
App::uses('CakeEventManager', 'Event');
App::uses('CakeEventListener', 'Event');

/**
 * Dispatcher converts Requests into controller actions.  It uses the dispatched Request
 * to locate and load the correct controller.  If found, the requested action is called on
 * the controller.
 *
 * @package       Cake.Routing
 */
class Dispatcher implements CakeEventListener {

/**
 * Event manager, used to handle dispatcher filters
 *
 * @var CakeEventMaanger
 */
	protected $_eventManager;

/**
 * Constructor.
 *
 * @param string $base The base directory for the application. Writes `App.base` to Configure.
 */
	public function __construct($base = false) {
		if ($base !== false) {
			Configure::write('App.base', $base);
		}
	}

/**
 * Returns the CakeEventManager instance or creates one if none was
 * creted. Attaches the default listeners and filters
 *
 * @return CakeEventmanger
 */
	public function getEventManager() {
		if (!$this->_eventManager) {
			$this->_eventManager = new CakeEventManager();
			$this->_eventManager->attach($this);
			$this->_attachFilters($this->_eventManager);
		}
		return $this->_eventManager;
	}

/**
 * Returns the list of events this object listents to.
 *
 * @return array
 */
	public function implementedEvents() {
		return array('Dispatcher.beforeDispatch' => 'parseParams');
	}

/**
 * Attaches all event listeners for this dispatcher instance. Loads the
 * dispatcher filters from the configured locations.
 *
 * @param CakeEventManager $manager
 * @return void
 * @throws MissingDispatcherFilterException
 */
	protected function _attachFilters($manager) {
		$filters = Configure::read('Dispatcher.filters');
		if (empty($filters)) {
			return;
		}

		foreach ($filters as $filter) {
			if (is_string($filter)) {
				$filter = array('callable' => $filter);
			}
			if (is_string($filter['callable'])) {
				list($plugin, $callable) = pluginSplit($filter['callable'], true);
				App::uses($callable, $plugin . 'Routing/Filter');
				if (!class_exists($callable)) {
					throw new MissingDispatcherFilterException($callable);
				}
				$manager->attach(new $callable);
			} else {
				$on = strtolower($filter['on']);
				$options = array();
				if (isset($filter['priority'])) {
					$options = array('priority' => $filter['priority']);
				}
				$manager->attach($filter['callable'], 'Dispatcher.' . $on . 'Dispatch', $options);
			}
		}
	}

/**
 * Dispatches and invokes given Request, handing over control to the involved controller. If the controller is set
 * to autoRender, via Controller::$autoRender, then Dispatcher will render the view.
 *
 * Actions in CakePHP can be any public method on a controller, that is not declared in Controller.  If you
 * want controller methods to be public and in-accessible by URL, then prefix them with a `_`.
 * For example `public function _loadPosts() { }` would not be accessible via URL.  Private and protected methods
 * are also not accessible via URL.
 *
 * If no controller of given name can be found, invoke() will throw an exception.
 * If the controller is found, and the action is not found an exception will be thrown.
 *
 * @param CakeRequest $request Request object to dispatch.
 * @param CakeResponse $response Response object to put the results of the dispatch into.
 * @param array $additionalParams Settings array ("bare", "return") which is melded with the GET and POST params
 * @return string|void if `$request['return']` is set then it returns response body, null otherwise
 * @throws MissingControllerException When the controller is missing.
 */
	public function dispatch(CakeRequest $request, CakeResponse $response, $additionalParams = array()) {
		if ($request->requested && permanentCached($this->here)) {
			return;
		}
		if ($this->asset($request->url, $response)) {
			return;
		}
		$beforeEvent = new CakeEvent('Dispatcher.beforeDispatch', $this, compact('request', 'response', 'additionalParams'));
		$this->getEventManager()->dispatch($beforeEvent);

		$request = $beforeEvent->data['request'];
		if ($beforeEvent->result instanceof CakeResponse) {
			if (isset($request->params['return'])) {
				return $response->body();
			}
			$response->send();
			return;
		}

		$controller = $this->_getController($request, $response);

		if (!($controller instanceof Controller)) {
			throw new MissingControllerException(array(
				'class' => Inflector::camelize($request->params['controller']) . 'Controller',
				'plugin' => empty($request->params['plugin']) ? null : Inflector::camelize($request->params['plugin'])
			));
		}
// rajesh_04ag02 // 2009-02-20 // for securing links with random hash
// another fix in router.php#851
        $t_action = $request->params['action'];
        if (!empty($request->params['prefix'])) {
			$t_action = substr($request->params['action'], strlen($request->params['prefix'] . '_'));
        }
        if (($_hashSecuredActions = Configure::read('site._hashSecuredActions')) && in_array($t_action, $_hashSecuredActions)) {
			if(!empty($request->params['controller']))
			{
				// Possible controllers for with name job
				$job_alternate_name = strtolower(Configure::read('job.job_alternate_name'));
				$job_alternate_name  = !empty($job_alternate_name) ? $job_alternate_name : 'jobs';
				$job_alternate_name_plural =  Inflector::pluralize($job_alternate_name);
				$job_alternate_name_singular =  Inflector::singularize($job_alternate_name);
				$job_array = array(
					'jobs',
					'job_orders',
					'job_favorites',
					'job_categories',
					'job_feedbacks',
					'job_flags',
					'job_flag_categories',
					'job_views',
					'job_order_disputes'
				);
				// Possible controllers for with name request
				$request_alternate_name = strtolower(Configure::read('request.request_alternate_name'));
				$request_alternate_name  = !empty($request_alternate_name) ? $request_alternate_name : 'requests';
				$request_alternate_name_plural =  Inflector::pluralize($request_alternate_name);
				$request_alternate_name_singular =  Inflector::singularize($request_alternate_name);
				$request_array = array(
					'requests',
					'request_orders',
					'request_favorites',
					'request_categories',
					'request_feedbacks',
					'request_flags',
					'request_flag_categories',
					'request_views',
				);
				if(in_array($request->params['controller'], $job_array))
				{
					if($request->params['controller'] == 'jobs')
						$request->params['controller'] = $job_alternate_name_plural;
					else
					{
						$url_exploded= explode('_',$request->params['controller']);
						unset($url_exploded[0]);
						$request->params['controller'] = $job_alternate_name_singular.'_' . implode('_',$url_exploded) ;
					}
				}
				if(in_array($request->params['controller'], $request_array))
				{
					if($request->params['controller'] == 'requests')
						$request->params['controller'] = $request_alternate_name_plural;
					else
					{
						$url_exploded= explode('_',$request->params['controller']);
						unset($url_exploded[0]);
						$request->params['controller'] = $request_alternate_name_singular.'_' . implode('_',$url_exploded) ;
					}
				}
			}
			//pr($request->params);
            $t_params = array_merge(array(
                $request->params['controller'],
                $t_action,
            ) , $request->params['pass']);
            $passed_secure_hash = array_pop($t_params);
            array_pop($request->params['pass']);
            $expected_secure_hash = md5(Configure::read('Security.salt') . 'secureurlhash' . implode('|', $t_params));
            if (strcmp($passed_secure_hash, $expected_secure_hash) != 0) {
                throw new NotFoundException(__l('Invalid request'));
            }
        }
//<--
		$response = $this->_invoke($controller, $request, $response);
		if (isset($request->params['return'])) {
			return $response->body();
		}

		$afterEvent = new CakeEvent('Dispatcher.afterDispatch', $this, compact('request', 'response'));
		$this->getEventManager()->dispatch($afterEvent);
		$afterEvent->data['response']->send();
	}

/**
 * Initializes the components and models a controller will be using.
 * Triggers the controller action, and invokes the rendering if Controller::$autoRender is true and echo's the output.
 * Otherwise the return value of the controller action are returned.
 *
 * @param Controller $controller Controller to invoke
 * @param CakeRequest $request The request object to invoke the controller for.
 * @param CakeResponse $response The response object to receive the output
 * @return CakeResponse te resulting response object
 */
	protected function _invoke(Controller $controller, CakeRequest $request, CakeResponse $response) {
		$controller->constructClasses();
		$controller->startupProcess();

		$render = true;
		$result = $controller->invokeAction($request);
		if ($result instanceof CakeResponse) {
			$render = false;
			$response = $result;
		}
		// Siva // to set yadis location in root
		if ($request->url == '') {
			$response->yadis = Router::url(array(
				'controller' => 'devs',
				'action' => 'yadis',
				'ext' => 'xml'
			), true);
		}
		if ($render && $controller->autoRender) {
			$response = $controller->render();
		} elseif ($response->body() === null) {
			$response->body($result);
		}
		$controller->shutdownProcess();

		return $response;
	}

/**
 * Applies Routing and additionalParameters to the request to be dispatched.
 * If Routes have not been loaded they will be loaded, and app/Config/routes.php will be run.
 *
 * @param CakeEvent $event containing the request, response and additional params
 * @return void
 */
	public function parseParams($event) {
		$request = $event->data['request'];
		Router::setRequestInfo($request);
		if (count(Router::$routes) == 0) {
			$namedExpressions = Router::getNamedExpressions();
			extract($namedExpressions);
			$this->_loadRoutes();
		}

		$params = Router::parse($request->url);
		$request->addParams($params);

		if (!empty($event->data['additionalParams'])) {
			$request->addParams($event->data['additionalParams']);
		}
	}

/**
 * Get controller to use, either plugin controller or application controller
 *
 * @param CakeRequest $request Request object
 * @param CakeResponse $response Response for the controller.
 * @return mixed name of controller if not loaded, or object if loaded
 */
	protected function _getController($request, $response) {
		$ctrlClass = $this->_loadController($request);
		if (!$ctrlClass) {
			return false;
		}
		$reflection = new ReflectionClass($ctrlClass);
		if ($reflection->isAbstract() || $reflection->isInterface()) {
			return false;
		}
		return $reflection->newInstance($request, $response);
	}

/**
 * Load controller and return controller classname
 *
 * @param CakeRequest $request
 * @return string|bool Name of controller class name
 */
	protected function _loadController($request) {
		$pluginName = $pluginPath = $controller = null;
		if (!empty($request->params['plugin'])) {
			$pluginName = $controller = Inflector::camelize($request->params['plugin']);
			$pluginPath = $pluginName . '.';
		}
		if (!empty($request->params['controller'])) {
			$controller = Inflector::camelize($request->params['controller']);
		}
		if ($pluginPath . $controller) {
			$class = $controller . 'Controller';
			App::uses('AppController', 'Controller');
			App::uses($pluginName . 'AppController', $pluginPath . 'Controller');
			App::uses($class, $pluginPath . 'Controller');
			if (class_exists($class)) {
				return $class;
			}
		}
		return false;
	}

/**
 * Loads route configuration
 *
 * @return void
 */
	protected function _loadRoutes() {
		include APP . 'Config' . DS . 'routes.php';
	}

/**
 * Checks if a requested asset exists and sends it to the browser
 *
 * @param string $url Requested URL
 * @param CakeResponse $response The response object to put the file contents in.
 * @return boolean True on success if the asset file was found and sent
 */
	public function asset($url, CakeResponse $response) {
		if (strpos($url, '..') !== false || strpos($url, '.') === false) {
			return false;
		}
		$filters = Configure::read('Asset.filter');
		$isCss = (
			strpos($url, 'ccss/') === 0 ||
			preg_match('#^(theme/([^/]+)/ccss/)|(([^/]+)(?<!css)/ccss)/#i', $url)
		);
		$isJs = (
			strpos($url, 'cjs/') === 0 ||
			preg_match('#^/((theme/[^/]+)/cjs/)|(([^/]+)(?<!js)/cjs)/#i', $url)
		);
		if (($isCss && empty($filters['css'])) || ($isJs && empty($filters['js']))) {
			$response->statusCode(404);
			$response->send();
			return true;
		} elseif ($isCss) {
			include WWW_ROOT . DS . $filters['css'];
			return true;
		} elseif ($isJs) {
			include WWW_ROOT . DS . $filters['js'];
			return true;
		}
		$pathSegments = explode('.', $url);
		$ext = array_pop($pathSegments);
		$parts = explode('/', $url);
		$assetFile = null;

		if ($parts[0] === 'theme') {
			$themeName = $parts[1];
			unset($parts[0], $parts[1]);
			$fileFragment = urldecode(implode(DS, $parts));
			$path = App::themePath($themeName) . 'webroot' . DS;
			if (file_exists($path . $fileFragment)) {
				$assetFile = $path . $fileFragment;
			}
		} else {
			$plugin = Inflector::camelize($parts[0]);
			if (CakePlugin::loaded($plugin)) {
				unset($parts[0]);
				$fileFragment = urldecode(implode(DS, $parts));
				$pluginWebroot = CakePlugin::path($plugin) . 'webroot' . DS;
				if (file_exists($pluginWebroot . $fileFragment)) {
					$assetFile = $pluginWebroot . $fileFragment;
				}
			}
		}

		if ($assetFile !== null) {
			$path_arr = pathinfo($url);
			$path = APP . 'webroot' . DS . $path_arr['dirname'];
			$destination = APP . 'webroot' . DS . $url;
			if (!class_exists('Folder')) {
				App::uses('Folder', 'Utility');
			}
			$folder = new Folder();
			$path = str_replace('/', DS, $path);
			$folder->create($path);
			@copy($assetFile, $destination);
			$this->_deliverAsset($response, $assetFile, $ext);
			return true;
		}
		return false;
	}

/**
 * Sends an asset file to the client
 *
 * @param CakeResponse $response The response object to use.
 * @param string $assetFile Path to the asset file in the file system
 * @param string $ext The extension of the file to determine its mime type
 * @return void
 */
	protected function _deliverAsset(CakeResponse $response, $assetFile, $ext) {
		ob_start();
		$compressionEnabled = Configure::read('Asset.compress') && $response->compress();
		if ($response->type($ext) == $ext) {
			$contentType = 'application/octet-stream';
			$agent = env('HTTP_USER_AGENT');
			if (preg_match('%Opera(/| )([0-9].[0-9]{1,2})%', $agent) || preg_match('/MSIE ([0-9].[0-9]{1,2})/', $agent)) {
				$contentType = 'application/octetstream';
			}
			$response->type($contentType);
		}
		if (!$compressionEnabled) {
			$response->header('Content-Length', filesize($assetFile));
		}
		$response->cache(filemtime($assetFile));
		$response->send();
		ob_clean();
		if ($ext === 'css' || $ext === 'js') {
			include $assetFile;
		} else {
			readfile($assetFile);
		}

		if ($compressionEnabled) {
			ob_end_flush();
		}
	}
}
