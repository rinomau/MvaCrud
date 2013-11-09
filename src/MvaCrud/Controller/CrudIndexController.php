<?php

namespace MvaCrud\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class CrudIndexController extends AbstractActionController
{
    private   $s_entityName;
    
    protected $I_service;
    protected $I_form;
    
    protected $redirectAfterProcess = true;
    
    // Variables used to customize index page
    protected $s_indexTitle;
    protected $s_indexTemplate;
    
    // Variables used to customize detail entity page
    protected $s_detailTitle;
    protected $s_detailTemplate;

    // Variables used to customize create new entity page
    protected $s_newTitle;
    protected $s_newTemplate;
    
    // Variables used to customize edit entity page
    protected $s_editTitle;
    protected $s_editTemplate;
    
    // Variables used to customize process of insert/update form
    protected $s_processErrorTitle;
    protected $s_processErrorTemplate;
    protected $s_processRouteRedirect;
    
    // Variables used to customize delete action
    protected $s_deleteRouteRedirect;
    
    // Variables used to display flash messages to user
    protected $s_flashMessageNew;
    protected $s_flashMessageUpdate;
    protected $s_flashMessageDelete;
    
    protected $as_config;
    protected $s_namespace;
    protected $s_module;
    
    public function __construct($s_entityName, $I_service, $I_form, $as_config) {
        $this->s_entityName = $s_entityName;
        $this->I_service = $I_service;
        $this->I_form = $I_form;
        
        $this->as_config = $as_config;
        
        $as_namespace = explode('\\',get_class($this));
        $this->s_namespace = $as_namespace[0];
        $this->s_module = strtolower(preg_replace('/([a-z])([A-Z])/', '$1-$2', $this->s_namespace));
        
        // IndexAction
        $this->s_indexTitle = $this->getDefaultValue('s_indexTitle',$this->s_namespace);
        $this->s_indexTemplate = $this->getDefaultValue('s_indexTemplate',$this->s_namespace);
        
        // NewAction
        $this->s_newTitle = $this->getDefaultValue('s_newTitle',$this->s_namespace);
        $this->s_newTemplate = $this->getDefaultValue('s_newTemplate',$this->s_namespace);
        
        // EditAction
        $this->s_editTitle = $this->getDefaultValue('s_editTitle',$this->s_namespace);
        $this->s_editTemplate = $this->getDefaultValue('s_editTemplate',$this->s_namespace);
        
        // DetailAction
        $this->s_detailTitle = $this->getDefaultValue('s_detailTitle',$this->s_namespace);
        $this->s_detailTemplate = $this->getDefaultValue('s_detailTemplate',$this->s_namespace);
        
        // Flash messages
        $this->s_flashMessageNew = $this->getDefaultValue('s_flashMessageNew',$this->s_namespace);
        $this->s_flashMessageUpdate = $this->getDefaultValue('s_flashMessageUpdate',$this->s_namespace);
        $this->s_flashMessageDelete = $this->getDefaultValue('s_flashMessageDelete',$this->s_namespace);
        
        // DeleteAction
        // @todo Alert on delete true/false
        // @todo Confirm on delete true/false
            // Confirm title
            // Confirm template

        // Process Delete Action
            // Redirect on delete true false
                $this->s_deleteRouteRedirect    = $this->getDefaultValue('s_deleteRouteRedirect',$this->s_namespace);
            // @todo Success page
            // @todo Success template
        
        // Process New, Edit Action
            // Error page
            $this->s_processErrorTitle = $this->getDefaultValue('s_processErrorTitle',$this->s_namespace);
            $this->s_processErrorTemplate  = $this->getDefaultValue('s_processErrorTemplate',$this->s_namespace);
        
            // Success page or redirect route
            // @todo Redirect on success true/false
                $this->s_processRouteRedirect   = $this->getDefaultValue('s_processRouteRedirect',$this->s_namespace);;
            // @todo Success title
            // @todo Success template
                
    }
    
    // *************************************************************************
    // CRUD OPERATIONS
    // *************************************************************************
    
    public function indexAction(){
        $I_view = new ViewModel(array(
            's_module' => $this->s_module,
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
        $this->flashMessenger()->setNamespace($this->s_entityName)->addMessage($this->s_flashMessageDelete);
        return $this->crudRedirect('delete');
    }
    
    public function detailAction(){
        $I_entity = $this->getEntityFromQuerystring();
        
        $I_view = new ViewModel(array(
            'I_entity' => $I_entity, 
            's_title' => $this->s_detailTitle,
            'as_messages' => $this->flashMessenger()->setNamespace($this->s_entityName)->getMessages()
            ));
        $I_view->setTemplate($this->s_detailTemplate);
        return $I_view;
    }
    
    public function processAction($custom_data=null){
        if ($this->request->isPost()) {
            // get post data
            $as_post = $this->request->getPost()->toArray();

            // Add custom data from children classes
            if ($custom_data != null ){
                $as_post = array_merge($as_post,$custom_data);
            }
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
            
            // @fixme Spostare quanto segue in un metodo a se che possa essere richiamato
            // dalle classi figlie che fanno la validazione per conto proprio in modo
            // da non doverla fare due volte

            // use service to save data
            $I_entity = $this->I_service->upsertEntityFromArray($as_post);
            
            if ( $as_post['id'] > 0 ) {
                $this->flashMessenger()->setNamespace($this->s_entityName)->addMessage($this->s_flashMessageUpdate);
            } else {
                $this->flashMessenger()->setNamespace($this->s_entityName)->addMessage($this->s_flashMessageNew);
            }
        
            if ($this->redirectAfterProcess){
                return $this->crudRedirect('process');
            } else {
                return true;
            }
        }
        
        $this->getResponse()->setStatusCode(404);
        return;
    }
    
    // *************************************************************************
    // UTILITY FUNCTIONS
    // *************************************************************************
    
    /*
     * Protected methods
     */
    protected function getEntityFromQuerystring($I_service=null) {
        // Se non gli passo un service manager specifico usa quello di default
        if ( $I_service === null){
            $I_service = $this->I_service;
        }
        
        $i_id = (int)$this->params('id');
        
        if (empty($i_id) || $i_id <= 0){
            $this->getResponse()->setStatusCode(404);    //@todo there is a better way?
                                                         // Probably triggering Not Found Event SM
                                                         // Zend\Mvc\Application: dispatch.error 
            return;
        }
        $I_entity = $I_service->getEntity($i_id);
        return $I_entity;
    }

    protected function getEntity($i_id,$I_service=null) {
        // Se non gli passo un service manager specifico usa quello di default
        if ( $I_service === null){
            $I_service = $this->I_service;
        }
        $I_entity = $I_service->getEntity($i_id);
        return $I_entity;
    }
    
    private function crudRedirect($s_action){
        $as_routeParams =  $this->getEvent()->getRouteMatch()->getParams();
        //print_r($as_routeParams);
        if (isset($as_routeParams['module'])){
            $s_module = $as_routeParams['module'];
        }
        else{
            $s_module = '';
        }
        
        
        switch ($s_action){
            case 'process':
                // *********************************************************************
                // // @fixme A COSA SERVE QUESTO IF? COME VIENE VALORIZZATO IL S_MODULE ???
                // *********************************************************************
                if ($this->s_processRouteRedirect != 'mva-crud') {
                    $this->s_processRouteRedirectParams['module'] = $s_module;
                    return $this->redirect()->toRoute($this->s_processRouteRedirect, $this->s_processRouteRedirectParams);
                }
                else {
                    return $this->redirect()->toRoute($s_module, array('module' => $s_module));
                }
                break;
            case 'delete':
                if ($this->s_deleteRouteRedirect != 'mva-crud') {
                    $this->s_deleteRouteRedirectParams['module'] = $s_module;
                    return $this->redirect()->toRoute($this->s_deleteRouteRedirect, $this->s_deleteRouteRedirectParams);
                }
                else {
                    return $this->redirect()->toRoute($s_module,array('module' => $s_module));
                }
                break;
            default:
                throw new \Exception('Invalid redirect action');
                
        }
    }
    
    /**
     * 
     * Legge il valore di default dei parametri di configurazione del modulo.
     * I valori possono essere definiti con la chiave 'MvaCrud' e valgono globalmente
     * oppure con la sottochiave __NAMESPACE__ e valgono per le entità di quel namespace
     * Nel module.config.php posso definire
     * 'MvaCrud' => array() // configurazione globale
     * oppure
     * 'MvaCrud' => array( __NAMESPACE__ => array()) // configurazione locale al namespace del modulo
     * oppure
     * definire il valore delle variabili direttamente a livello di controller nel modulo concreto che estende questo
     * dopo aver chiamato il costruttore
     * 
     * @param string $s_variableName
     * @param string $s_namespace
     * @return type
     */
    private function getDefaultValue($s_variableName,$s_namespace){
        if (isset($this->as_config[$s_namespace][$s_variableName])) {
            return $this->$s_variableName = $this->as_config[$s_namespace][$s_variableName];
        }  else {
            return $this->$s_variableName = $this->as_config[$s_variableName];
        }
    }
    
    
}
