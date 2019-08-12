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
include_once ('Attachment.php');
class Image extends Attachment
{
    public $name = 'Image';
    var $useTable = 'attachments';
    public $actsAs = array(
        //		'WhoDunnit',
        /*		'Slug' => array (
        'label' =>'description',
        'overwrite' => true,
        'unique' => false
        ),
        */
        'ImageUpload'
    );
}
?>
