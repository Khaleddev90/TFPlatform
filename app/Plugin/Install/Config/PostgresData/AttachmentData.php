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
class AttachmentData {

	public $table = 'attachments';

	public $records = array(
		array(
			'id' => '1',
			'created' => '2010-04-28 11:55:48',
			'modified' => '2010-04-28 11:55:48',
			'class' => 'Job',
			'foreign_id' => '0',
			'filename' => 'default_job.jpg',
			'dir' => 'Job/0',
			'mimetype' => 'image/jpeg',
			'filesize' => '1087',
			'height' => '50',
			'width' => '50',
			'thumb' => '',
			'description' => ''
		),
		array(
			'id' => '2',
			'created' => '2010-04-28 11:55:48',
			'modified' => '2010-04-28 11:55:48',
			'class' => 'UserAvatar',
			'foreign_id' => '0',
			'filename' => 'default_avatar.jpg',
			'dir' => 'UserAvatar/0',
			'mimetype' => 'image/jpeg',
			'filesize' => '15011',
			'height' => '265',
			'width' => '380',
			'thumb' => '',
			'description' => ''
		),
	);

}
