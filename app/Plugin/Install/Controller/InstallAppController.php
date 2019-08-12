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
class InstallAppController extends AppController
{
    public function beforeFilter()
    {
        $this->Components->unload('Cms');
        $this->Components->unload('Auth');
    }
}
