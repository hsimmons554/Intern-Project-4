<?php
class Book_BookController extends Ia_Controller_Action_Abstract
{

    public function init()
    {
        $this->view->singular = 'book';
        $this->view->plural = 'books';
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
        $this->entity = new Book\Entity\Book;
        $this->createForm = new Book_Form_Book_CreateUpdate;
        $this->updateForm = new Book_Form_Book_CreateUpdate;
        parent::init();
        $this->addFilterWidget('activeInactive','e.active',1);
    }

}
