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

    public function showPersonStatesAction()
    {
      $this->_helper->layout()->disableLayout();
      $this->_helper->viewRenderer->setNoRender(true);

      $id = $this->_getParam('id');
      if($id <= 0 || !is_numeric($id))
      {
        $array['error'] = "Problem retrieving user's ID";
        $this->_helper->json($array);
      } else {
        $user = $this->entity->getEntityManager()->getRepository(
                    'Training\Entity\Person')->find($id);

        $array = [];
        foreach($user->states as $state)
        {
          $array[] = $state->state_name;
        }
        sort($array);
        return $this->_helper->json($array);
      }
    }

    public function singlePersonAction()
    {
      $this->_helper->layout()->disableLayout();
      $this->_helper->viewRenderer->setNoRender(true);

      $id = $this->_getParam('id', 1);
      // If invalid id default to 1
      if($id <= 0 || !is_numeric($id)){
        $id = 1;
      }
      $i = $this->entity->getEntityManager()->getRepository(
                          'Training\Entity\Person')->find($id);

      $array = [];
        $array['id'] = $id;
        $array['first_name'] = $i->first_name;
        $array['last_name'] = $i->last_name;
        $array['favorite_food'] = $i->favorite_food;

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
        // Create new Record for people table
        $user = new Training\Entity\Person();
        $user->first_name = $fname;
        $user->last_name = $lname;
        $user->favorite_food = $food;
        $this->entity->getEntityManager()->persist($user);
        $this->entity->getEntityManager()->flush();

        // Return user to index
        $id = $user->id;
        $array['id'] = $id;
        $array['first_name'] = $fname;
        $array['last_name'] = $lname;
        $array['favorite_food'] = $food;
        $this->_helper->json($array);
      }
    }

    public function storeVisitsAction()
    {
      $this->_helper->layout()->disableLayout();
      $this->_helper->viewRenderer->setNoRender(true);

      $prs_id = $this->_getParam('prs_id');
      $ste_id = $this->_getParam('ste_id');

      if($prs_id == NULL || $prs_id === FALSE || $ste_id == NULL ||
         $ste_id === FALSE)
         {
           $array['error'] = "Problem retrieving user's visit's information.";
           $this->_helper->json($array);
         } else {
           // Create the visit record
           $user = $this->entity->getEntityManager()->getRepository(
                    'Training\Entity\Person')->find($prs_id);
           $state = $this->entity->getEntityManager()->getRepository(
                    'Training\Entity\State')->find($ste_id);
           $user->getStates()->add($state);
           $state->getPeople()->add($user);
           $this->entity->getEntityManager()->flush();
         }
    }
}
