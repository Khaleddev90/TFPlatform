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
class TwitterConsumer extends AbstractConsumer
{
    public function __construct()
    {
        parent::__construct(Configure::read('twitter.consumer_key') , Configure::read('twitter.consumer_secret'));
    }
}
?>