<?php

namespace MvaCrud\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    
    protected   $I_service;
    protected   $I_form;
    private     $s_entityName;
    
    protected $s_indexTemplate;
    protected $s_newTitle;
    protected $s_newTemplate;
    protected $s_editTitle;
    protected $s_editTemplate;
    
    protected $s_processErrorTitle;
    protected $s_processErrorTemplate;

    protected $s_processRouteRedirect;
    protected $s_deleteRouteRedirect;
    
    /**
     * @var ModuleOptions
     */
    protected $options;
    
    public function __construct($s_entityName, $I_service, $I_form) {
        $this->s_entityName = $s_entityName;
        $this->I_service = $I_service;
        $this->I_form = $I_form;
        
        // Set defaults variables
        $this->s_indexTemplate    = 'crud/index/index';
        
        $this->s_newTitle       = 'New '.$this->s_entityName;
        $this->s_newTemplate    = 'crud/index/default-form';

        $this->s_editTitle      = 'Edit '.$this->s_entityName;
        $this->s_editTemplate   = 'crud/index/default-form';
        
        $this->s_processErrorTitle = 'Some errors during entity editing';
        $this->s_processErrorTemplate  = 'crud/index/default-form';
        
        $this->s_processRouteRedirect   = 'crud';
        $this->s_deleteRouteRedirect    = 'crud';
        
    }
    
    public function indexAction(){
        
        if ($this->getOptions()->getIndexPageTitle() ) {
            $this->s_indexTitle = $this->getOptions()->getIndexPageTitle();
        } else {
            $this->s_indexTitle = $this->s_entityName.' list';
        }

        $I_view = new ViewModel(array(
            's_title' => $this->s_indexTitle,
            'aI_entities' => $this->I_service->getAllEntities(),
            'as_messages' => $this->flashMessenger()->setNamespace($this->s_entityName)->getMessages(),
        ));
        $I_view->setTemplate($this->s_indexTemplate);
        return $I_view;
    }
    
    public function newAction(){
        $I_view = new ViewModel(array('form' => $this->I_form, 'title' => $this->s_newTitle));
        $I_view->setTemplate($this->s_newTemplate);
        return $I_view;
    }
    
    public function editAction($I_entity=null){
        if ( null == $I_entity ){
            $I_entity = $this->getEntityFromQuerystring();
        }
                
        // bind entity values to form
        $this->I_form->bind($I_entity);
        
        $I_view = new ViewModel(array('form' => $this->I_form, 'title' => $this->s_editTitle));
        $I_view->setTemplate($this->s_editTemplate);
        return $I_view;
    }
    
    public function deleteAction(){
        $I_entity = $this->getEntityFromQuerystring();
        $this->I_service->deleteEntity($I_entity);
        return $this->redirect()->toRoute($this->s_deleteRouteRedirect);
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
                                              'title' => $this->s_processErrorTitle));
                $I_view->setTemplate($this->s_processErrorTemplate);
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
    
    /**
     * set options
     *
     * @param UserControllerOptionsInterface $options
     * @return UserController
     */
    public function setOptions(\MvaCrud\Options\ModuleOptions $options )
    {
        $this->options = $options;
        return $this;
    }

    /**
     * get options
     *
     * @return UserControllerOptionsInterface
     */
    public function getOptions()
    {
        if (!$this->options instanceof \MvaCrud\Options\ModuleOptions) {
            $this->setOptions($this->getServiceLocator()->get('mvacrud_module_options'));
        }
        return $this->options;
    }

}
