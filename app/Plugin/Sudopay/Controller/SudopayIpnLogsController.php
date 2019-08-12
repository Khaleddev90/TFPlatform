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
class SudopayIpnLogsController extends AppController
{
    var $name = 'SudopayIpnLogs';
    function admin_index() 
    {
        $this->pageTitle = __l('Sudopay IPN Logs');
		$conditions = array(
            'SudopayIpnLog.post_variable !=' => ''
        );
        $this->paginate = array(
			'conditions' => $conditions,
            'order' => array(
                'SudopayIpnLog.id' => 'desc'
            ) ,
            'recursive' => 0
        );
        $this->set('sudopayIpnLogs', $this->paginate());
    }
}
?>
