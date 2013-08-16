<?php

namespace MvaCrud\Service;

use MvaCrud\Service\CrudServiceInterface;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;

class CrudService implements CrudServiceInterface {
    
    private $I_entityRepository;
    private $I_entityManager;
    private $I_entity;
    private $hydrator;
        
    
    public function __construct($I_entityManager,$I_entityRepository,$I_entity) {
        $this->I_entityRepository  = $I_entityRepository;
        $this->I_entityManager = $I_entityManager;
        $this->I_entity = $I_entity;
        $this->hydrator = new \DoctrineModule\Stdlib\Hydrator\DoctrineObject($this->I_entityManager,get_class($I_entity));
        
    }

    public function getEntity($i_id){
        $I_entity = $this->I_entityRepository->find($i_id);

        if ($I_entity === null ){
            throw new \Exception('Entity not found');    //@todo throw custom exception type
        }

        return $I_entity;
    }
    
    public function getEntityDetailAsArray($I_entity){
        return $this->hydrator->extract($I_entity);
    }

    public function getAllEntities(){
        return $this->I_entityRepository->findAll();
    }
    
    public function upsertEntityFromArray(array $am_formData){

        $I_entity = NULL;
        if (isset($am_formData['id']) && 
            is_numeric($am_formData['id']) && 
            $am_formData['id'] > 0){
            $I_entity = $this->getEntity($am_formData['id']);
        }

        if ( $I_entity === null ) {
            $I_entity = $this->I_entity;
        }

        $this->hydrator->hydrate($am_formData,$I_entity);
        
        $this->I_entityManager->persist($I_entity);
        $this->I_entityManager->flush();
    
        return $I_entity;
    }
    
    public function saveEntity($I_entity){
        $this->I_entityManager->persist($I_entity);
        $this->I_entityManager->flush();
        return $I_entity;
    }
    
    public function deleteEntity( $I_entity ) {
        $this->I_entityManager->remove($I_entity);
        $this->I_entityManager->flush();
    }
    
    public function find($i_id){
        return $this->I_entityRepository->find($i_id);
    }
    
    /**
     * Return an array id => nome of all entities
     * @return array
     */
    public function getTendina(){
        $as_lista = array();
        $aI_entities = $this->getAllEntities();
        foreach ($aI_entities as $I_entity )
        {
            $as_lista[$I_entity->getId()] = $I_entity->getNome();
        }
        return $as_lista;
    }
}
