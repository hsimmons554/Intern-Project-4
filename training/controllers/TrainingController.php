<?php
class Training_TrainingController extends Ia_Controller_Action_Abstract
{

    public function init()
    {
        $this->view->singular = 'training';
        $this->view->plural = 'trainings';
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
        $this->entity = new Training\Entity\Training;
        $this->createForm = new Training_Form_Training_CreateUpdate;
        $this->updateForm = new Training_Form_Training_CreateUpdate;
        parent::init();
        $this->addFilterWidget('activeInactive','e.active',1);
    }

    public function indexAction()
    {

    }

    public function peopleAction()
    {
      // Disable the rendering of the default view
      $this->_helper->layout()->disableLayout();
      $this->_helper->viewRenderer->setNoRender(true);

      $records = $this->entity->getEntityManager()->getRepository(
                                "Training\Entity\Person")->findAll();
      $array = [];
      foreach($records as $re)
      {
        $array[$re->id]['first_name'] = $re->first_name;
        $array[$re->id]['last_name'] = $re->last_name;
        $array[$re->id]['favorite_food'] = $re->favorite_food;
      }

      //Output the json
      $this->_helper->json($array);
    }

    public function statesAction()
    {
      // Disable the rendering of the default view
      $this->_helper->layout()->disableLayout();
      $this->_helper->viewRenderer->setNoRender(true);

      // Retrieve all the state records
      $records = $this->entity->getEntityManager()->getRepository(
                                "Training\Entity\State")->findAll();
      $array = [];
      foreach($records as $i)
      {
        $array[$i->id]['state_name'] = $i->state_name;
        $array[$i->id]['state_abbreviation'] = $i->state_abbreviation;
      }

      // Output the json
      $this->_helper->json($array);
    }

    public function singlePersonAction()
    {
      $this->_helper->layout()->disableLayout();
      $this->_helper->viewRenderer->setNoRender(true);

      $id = $this->_getParam('id', 1);
      $records = $this->entity->getEntityManager()->getRepository(
                                "Training\Entity\Person")->find($id);

      $array = [];
      foreach($records as $i)
      {
        $array[$i->id]['first_name'] = $i->first_name;
        $array[$i->id]['last_name'] = $i->last_name;
        $array[$i->id]['favorite_food'] = $i->favorite_food;
      }

      // Output the json
      $this->_helper->json($array);
    }

    public function storePeopleAction()
    {
      $this->_helper->layout()->disableLayout();
      $this->_helper->viewRenderer->setNoRender(true);

      $fname = $this->_getParam('first_name');
      $lname = $this->_getParam('last_name');
      $food = $this->_getParam('favorite_food');

      if($fname == NULL || $lname == NULL || $food == NULL)
      {
        $array['error'] = "Problem retrieving new user's Information";
        $this->_helper->json($array);
      } else {
        $user = new Person();
        $user->__set('first_name', $fname);
        $user->__set('last_name', $lname);
        $user->__set('favorite_food', $food);
        $user->persist();

        // Return user to index
        $id = $user->__get('id');
        $array['id'] = $id;
        $array['first_name'] = $fname;
        $array['last_name'] = $lname;
        $array['favorite_food'] = $food;
        $this->_helper->json($array);
      }
    }
}
