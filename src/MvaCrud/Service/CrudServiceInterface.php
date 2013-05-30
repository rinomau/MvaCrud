<?php

namespace MvaCrud\Service;

interface CrudServiceInterface
{
    /**
     * Get single entity
     *
     * @return found entity instance or null
     */
    public function getEntity($id);
    
    /**
     * Get all entities
     *
     * @return found entities instances or null
     */
    public function getAllEntities();
    
    /**
     * Inserts or updates Entity
     * 
     * @param array $am_formData
     * @return Ambigous <NULL, \MvaModuleTemplate\Entity\Dog>
     */
    public function upsertEntityFromArray(array $am_formData);
    
    /**
     * 
     * Delete an entity
     * 
     * @param type $I_entity
     */
    public function deleteEntity($I_entity);

}