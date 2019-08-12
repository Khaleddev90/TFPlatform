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
class CmsRouter
{
    /**
     * If Translate plugin is active,
     * an extra Route will be created for locale-based URLs
     *
     * For example,
     * http://yoursite.com/blog/post-title, and
     * http://yoursite.com/eng/blog/post-title
     *
     * Returns this object's routes array. Returns false if there are no routes available.
     *
     * @param string $route			An empty string, or a route string "/"
     * @param array $default		NULL or an array describing the default route
     * @param array $params			An array matching the named elements in the route to regular expressions which that element should match.
     * @return void
     */
    public static function connect($route, $default = array() , $params = array())
    {
        Router::connect($route, $default, $params);
        if ($route == '/') {
            $route = '';
        }
        if (Configure::read('Translate')) {
            Router::connect('/:locale' . $route, $default, array_merge(array(
                'locale' => '[a-z]{3}'
            ) , $params));
        }
    }
    /**
     * If you want your non-routed controler actions (like /users/add) to support locale based urls,
     * this method must be called AFTER all the routes.
     *
     * @return void
     */
    public static function localize()
    {
        if (Configure::read('Translate')) {
            Router::connect('/:locale/:controller/:action/*', array() , array(
                'locale' => '[a-z]{3}'
            ));
        }
    }
    /**
     * Load plugin routes
     *
     * @return void
     */
    public static function plugins()
    {
        $pluginRoutes = Configure::read('Hook.routes');
        if (!$pluginRoutes || !is_array(Configure::read('Hook.routes'))) {
            return;
        }
        $plugins = Configure::read('Hook.routes');
        foreach($plugins as $plugin) {
            $path = App::pluginPath($plugin) . 'Config' . DS . 'routes.php';
            if (file_exists($path)) {
                include $path;
            }
        }
    }
}
