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
class CronsController extends AppController
{
    public $name = 'Crons';
    public $components = array(
        'Cron',
    );
    function run_crons()
    {
        App::import('Core', 'ComponentCollection');
        $collection = new ComponentCollection();
        App::import('Component', 'cron');
        $this->Cron = new CronComponent($collection);
        $this->Cron->run_crons();
        $this->autoRender = false;
    }
    public function main()
    {
        $this->Cron->main();
        if (!empty($_GET['r'])) {
            $this->Session->setFlash(__l('Property status updated successfully') , 'default', null, 'success');
            $this->redirect(Router::url(array(
                'controller' => 'nodes',
                'action' => 'tools',
                'tools',
                'admin' => true
            ) , true));
        }
        $this->autoRender = false;
    }
    public function daily()
    {
        $this->Cron->daily();
        if (!empty($_GET['r'])) {
            $this->Session->setFlash(__l('Cron updated successfully') , 'default', null, 'success');
            $this->redirect(Router::url(array(
                'controller' => 'nodes',
                'action' => 'tools',
                'tools',
                'admin' => true
            ) , true));
        }
        $this->autoRender = false;
    }
}
?>