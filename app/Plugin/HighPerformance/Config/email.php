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
class EmailConfig
{
    public function __construct() 
    {
        $this->smtp = array(
            'host' => Configure::read('mail.smtp_host') ,
            'port' => Configure::read('mail.smtp_port') ,
            'username' => Configure::read('mail.smtp_username') ,
            'password' => Configure::read('mail.smtp_password') ,
            'transport' => 'SMTP',
        );
    }
}
?>
