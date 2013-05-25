<?php

namespace MvaCrud\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    
    protected $I_service;
    protected $I_form;
    private $s_entityName;
    
    protected $s_newActionTitle;
    protected $s_newFormTemplate;
    protected $s_editActionTitle;
    protected $s_editFormTemplate;
    protected $s_processActionErrorTitle;
    protected $s_processActionErrorForm;
    
    protected $s_processRouteRedirect;
    
    public function __construct($s_entityName, $I_service, $I_form) {
        $this->s_entityName = $s_entityName;
        $this->I_service = $I_service;
        $this->I_form = $I_form;
        
        // Set defaults variables
        $this->s_newActionTitle     = 'New '.$this->s_entityName;
        $this->s_newFormTemplate    = 'crud/index/default-form';

        $this->s_editActionTitle    = 'Edit '.$this->s_entityName;
        $this->s_editFormTemplate   = 'crud/index/default-form';
        
        $this->s_processActionErrorTitle = 'Some errors during entity editing';
        $this->s_processActionErrorForm  = 'crud/index/default-form';
        
        $this->s_processRouteRedirect = 'crud';
        
    }
    
    public function indexAction(){
        return new ViewModel(array(
            'aI_entities' => $this->I_service->getAllEntities(),
            'as_messages' => $this->flashMessenger()->setNamespace($this->s_entityName)->getMessages(),
        ));
    }
    
    public function newAction(){
        $I_view = new ViewModel(array('form' => $this->I_form, 'title' => $this->s_newActionTitle));
        $I_view->setTemplate($this->s_newFormTemplate);
        return $I_view;
    }
    
    public function editAction($I_entity=null){
        if ( null == $I_entity ){
            $I_entity = $this->getEntityFromQuerystring();
        }
                
        // bind entity values to form
        $this->I_form->bind($I_entity);
        
        $I_view = new ViewModel(array('form' => $this->I_form, 'title' => $this->s_editActionTitle));
        $I_view->setTemplate($this->s_editFormTemplate);
        return $I_view;
    }
    
    public function deleteAction(){
        $I_entity = $this->getEntityFromQuerystring();
        $this->I_service->deleteEntity($I_entity);
        return $this->redirect()->toRoute('crud');
    }
    
    public function processAction(){
        if ($this->request->isPost()) {
            // get post data
            $as_post = $this->request->getPost()->toArray();
            // fill form
            $this->I_form->setData($as_post);
            // check if form is valid
            if(!$this->I_form->isValid()) {
                // prepare view
                $I_view = new ViewModel(array('form'  => $this->I_form,
                                               'title' => $this->s_processActionErrorTitle));
                $I_view->setTemplate($this->s_processActionErrorForm);
                return $I_view;
            }
    
            // use service to save data
            $I_entity = $this->I_service->upsertEntityFromArray($as_post);
    
            if ( $as_post['id'] > 0 ) {
                $this->flashMessenger()->setNamespace($this->s_entityName)->addMessage($this->s_entityName . $I_entity->getName() . ' updated successfully');
            } else {
                $this->flashMessenger()->setNamespace($this->s_entityName)->addMessage($this->s_entityName . $I_entity->getName() . ' inserted successfully');
            }
            
            return $this->redirect()->toRoute($this->s_processRouteRedirect);
        }
        
        
        $this->getResponse()->setStatusCode(404);
        return;
    }
    
    
    /*
     * Private methods
     */
    
    private function getEntityFromQuerystring() {
        $i_id = (int)$this->params('id');
        
        if (empty($i_id) || $i_id <= 0){
            $this->getResponse()->setStatusCode(404);    //@todo there is a better way?
                                                         // Probably triggering Not Found Event SM
                                                         // Zend\Mvc\Application: dispatch.error 
            return;
        }
        
        $I_entity = $this->I_service->getEntity($i_id);
                
        return $I_entity;
        
    }
    
}
