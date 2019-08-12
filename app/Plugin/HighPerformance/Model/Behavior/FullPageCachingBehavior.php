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
class FullPageCachingBehavior extends ModelBehavior
{
    /**
     * Setup
     *
     * @param object $model
     * @param array  $config
     * @return void
     */
    public function beforeDelete(Model $model, $cascade = true) 
    {
        $this->deleteFullPageCache($model);
        parent::beforeDelete($model);
    }
    public function afterSave(Model $model, $created) 
    {
        $this->deleteFullPageCache($model);
        parent::afterSave($model, $created);
    }
    public function deleteFullPageCache($Model) 
    { 
		if ($Model->alias != 'OwnerUser') {
			if ($Model->alias == 'Job') {
				App::import('Model', 'Jobs.Job');
			} else {
				App::import('Model', $Model->alias);
			}
			$model_obj = new $Model->alias();
			$data = $model_obj->find('first', array(
				'conditions' => array(
					$Model->alias . '.id =' => $Model->data[$Model->alias]['id'],
				) ,
				'recursive' => -1
			));
			$url_arr = array();
			if ($Model->alias == 'Job') {
				$slug = $data[$Model->alias]['slug'];
				$url_arr = array(
					WWW_ROOT . DS . 'cache' . DS . 'job' . DS . $slug . DS . 'index.html',
					WWW_ROOT . DS . 'cache' . DS . 'jobs' . DS . 'browse' . DS . 'index.html',
					WWW_ROOT . DS . 'cache' . DS . 'index.html'
				);
				$dir = WWW_ROOT . 'cache' . DS . 'jobs';
				$this->_traverse_directory($dir, '');
			} elseif ($Model->alias == 'request') {
				$slug = $data[$Model->alias]['slug'];
				$url_arr = array(
					WWW_ROOT . DS . 'cache' . DS . 'request' . DS . $slug . DS . 'index.html',
					WWW_ROOT . DS . 'cache' . DS . 'requests' . DS . 'browse' . DS . 'index.html',
					WWW_ROOT . DS . 'cache' . DS . 'index.html'
				);
				$dir = WWW_ROOT . 'cache' . DS . 'requests';
				$this->_traverse_directory($dir, '');
			} else if ($Model->alias == 'User') {
				$slug = $data[$Model->alias]['username'];
				$url_arr = array(
					WWW_ROOT . DS . 'cache' . DS . 'user' . DS . $slug . DS . 'index.html'
				);
			}
			foreach($url_arr as $url) {
				@unlink($url);
			}
		}
    }
    public function _traverse_directory($dir, $dir_count) 
    {
        if (is_dir($dir)) {
            $handle = opendir($dir);
            while (false !== ($readdir = readdir($handle))) {
                if ($readdir != '.' && $readdir != '..') {
                    $path = $dir . '/' . $readdir;
                    if (is_dir($path)) {
                        @chmod($path, 0777);
                        ++$dir_count;
                        $this->_traverse_directory($path, $dir_count);
                    }
                    if (is_file($path)) {
                        @chmod($path, 0777);
                        @unlink($path);
                        //so that page wouldn't hang
                        flush();
                    }
                }
            }
            closedir($handle);
            @rmdir($dir);
            return true;
        }
    }
}
