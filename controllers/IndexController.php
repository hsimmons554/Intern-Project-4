<?php

class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        $this->doctrineContainer = \Zend_Registry::get('doctrine');

        /* Initialize action controller here */
    }

    public function indexAction()
    {
        if(\Ia\Config::get('index')){
            $parts = explode('_',\Ia\Config::get('index'));
            $this->view->module = $module = $parts[0];
            $this->view->controller = $controller = $parts[1];
            $this->view->action = $action = $parts[2];
            $this->view->params = $this->getRequest()->getParams();
            if(\Ia\Config::get('redirect_index')){
                $this->_helper->redirector->gotoRoute(array('module'=>$module,'controller'=>$controller,'action'=>$action));
            }
        }
    }

    public function getEntityManager()
    {
        if($this->_dc === null){
            $this->_dc = \Zend_Registry::get('doctrine');
        }
        if($this->_em == null){
            $this->_em = $this->_dc->getEntityManager();
        }
        if(!$this->_em->isOpen()){
            $this->_em = $this->_dc->resetEntityManager();
        }
        return $this->_em;
    }

    public function testAction()
    {
        $c = new \Celery\Celery(
            'localhost', /* Server */
            '', /* Login */
            '', /* Password */
            '2', /* vhost/dbname - 1 is used for caching */
            'celery', /* exchange */
            'celery', /* binding */
            6379, /* port */
            'redis' /* connector */
        );
        echo get_class($c);
        exit;
    }

}
