<?php

namespace MvaCrud\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use MvaCrud\Service\ServiceInterface;

class CrudPlugin extends AbstractPlugin
{
    /**
     * Check if exist a param id in the url with value > 0
     * @return type
     * @throws \Exception
     */
    public function getId()
    {
        $i_id = (int)$this->getController()->params('id');
        if ( $i_id <= 0 ){
            throw new \Exception ('Id not found or invalid');
        }
        return $i_id;
    }
    
    /**
     * Get a entity with id passed by id param in url
     * @param type $I_service
     * @return type
     * @throws \Exception
     */
    public function getEntity( ServiceInterface $I_service, $i_id = NULL ){
        if ( $i_id == NULL ){
            $i_id = $this->getId();
        }
        $I_entity = $I_service->getEntity($i_id);
        if ( $I_entity == NULL ){
            throw new \Exception ('Entity not found');
        }
        return $I_entity;
    }
    
    public function return404(){
        $this->getController()->getResponse()->setStatusCode(404);
        return;
    }

}