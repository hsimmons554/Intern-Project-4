<?php
class Intern_InternController extends Ia_Controller_Action_Abstract
{

    public function init()
    {
        $this->view->singular = 'intern';
        $this->view->plural = 'interns';
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
        $this->entity = new Intern\Entity\Intern;
        $this->createForm = new Intern_Form_Intern_CreateUpdate;
        $this->updateForm = new Intern_Form_Intern_CreateUpdate;
        parent::init();
        $this->addFilterWidget('activeInactive','e.active',1);
    }

    public function indexAction()
    {
      
    }
}
