<?php
class Dog_DogController extends Ia_Controller_Action_Abstract
{

    public function init()
    {
        $this->view->singular = 'dog';
        $this->view->plural = 'dogs';
        $this->view->columns = array('id'=>'Id','title'=>'Title','active'=>'Active');
        $this->view->formats = array(
                'active' => array('YesNo'),
            );
        $this->view->detailColumns = $this->view->columns;
        $this->view->actions = array(
            'view'=>$this->actions('view'),
            'edit'=>$this->actions('edit'),
            'delete'=>$this->actions('delete'),
        );
        $this->entity = new Dog\Entity\Dog;
        $this->createForm = new Dog_Form_Dog_CreateUpdate;
        $this->updateForm = new Dog_Form_Dog_CreateUpdate;
        parent::init();
        $this->addFilterWidget('activeInactive','e.active',1);
    }

    public function indexAction()
    {
      
    }

}
