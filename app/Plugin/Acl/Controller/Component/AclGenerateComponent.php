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
App::uses('Folder', 'Utility');
class AclGenerateComponent extends Component
{
    protected $controller = null;
    protected $folder = null;
    /**
     * @param object $controller controller
     * @param array  $settings   settings
     */
    public function initialize(Controller $controller) 
    {
        $this->controller = &$controller;
        $this->folder = new Folder;
    }
    /**
     * List all controllers (including plugin controllers)
     *
     * @return array
     */
    public function listControllers() 
    {
        $controllerPaths = array();
        // app/controllers
        $this->folder->path = APP . 'Controller' . DS;
        $controllers = $this->folder->read();
        foreach($controllers['1'] AS $c) {
            if (substr($c, strlen($c) -4, 4) == '.php') {
                $cName = str_replace('Controller.php', '', $c);
                if ($cName == 'App') {
                    continue; // skip AppController
                    
                }
                $controllerPaths[$cName] = APP . 'Controller' . DS . $c;
            }
        }
        // plugins/*/controllers/
        $this->folder->path = APP . 'Plugin' . DS;
        $plugins = $this->folder->read();
        foreach($plugins['0'] AS $p) {
            if ($p != 'Install') {
                if (!CakePlugin::loaded($p)) {
                    continue;
                }
                $this->folder->path = APP . 'Plugin' . DS . $p . DS . 'Controller' . DS;
                $pluginControllers = $this->folder->read();
                foreach($pluginControllers['1'] AS $pc) {
                    if (substr($pc, strlen($pc) -4, 4) == '.php') {
                        $pcName = str_replace('Controller.php', '', $pc);
                        if ($pcName == $p . 'App') {
                            continue; // skip PluginAppController
                            
                        }
                        $controllerPaths[$pcName] = APP . 'Plugin' . DS . $p . DS . 'Controller' . DS . $pc;
                    }
                }
            }
        }
        return $controllerPaths;
    }
    /**
     * List actions of a particular Controller.
     *
     * @param string  $name Controller name (the name only, without having Controller at the end)
     * @param string  $path full path to the controller file including file extension
     * @param boolean $all  default is false. it true, private actions will be returned too.
     *
     * @return array
     */
    public function listActions($name, $path) 
    {
        // base methods
        if (strstr($path, APP . 'Plugin')) {
            $plugin = $this->getPluginFromPath($path);
            $pacName = $plugin . 'AppController'; // pac - PluginAppController
            $pacPath = $plugin . '.Controller';
            App::uses($pacName, $pacPath);
            $baseMethods = get_class_methods($pacName);
        } else {
            $baseMethods = get_class_methods('AppController');
            $pacPath = 'Controller';
        }
        $controllerName = $name . 'Controller';
        App::uses($controllerName, $pacPath);
        $methods = get_class_methods($controllerName);
        // filter out methods
        if (!empty($methods)) {
            foreach($methods AS $k => $method) {
                if (strpos($method, '_', 0) === 0) {
                    unset($methods[$k]);
                    continue;
                }
                if (!empty($baseMethods) && in_array($method, $baseMethods)) {
                    unset($methods[$k]);
                    continue;
                }
            }
        }
        return $methods;
    }
    /**
     * Get plugin name from path
     *
     * @param string $path file path
     *
     * @return string
     */
    public function getPluginFromPath($path) 
    {
        $pathE = explode(DS, $path);
        $pluginsK = array_search('Plugin', $pathE);
        $pluginNameK = $pluginsK+1;
        $plugin = $pathE[$pluginNameK];
        return $plugin;
    }
}
