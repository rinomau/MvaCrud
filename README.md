mva-crud
========
Base module to extend to simplify crud development.

Usage
=====
Create a module with a doctrine entity
In your module extend CrudIndexController and CrudService

Your indexController:
```
class IndexController extends \MvaCrud\Controller\CrudIndexController {
    
    public function __construct($I_service, $I_form) {
        $entityName = 'Dog';
        parent::__construct($entityName, $I_service, $I_form);
    }
}
```
Your EntityService
```
class DogService extends \MvaCrud\Service\CrudService {
    
    public function __construct($I_entityManager) {
        $this->I_entityRepository  = $I_entityManager->getRepository('MvaModuleTemplate\Entity\Dog');
        $this->I_entityManager = $I_entityManager;
        $I_dog = new Dog();

        parent::__construct($this->I_entityManager,$this->I_entityRepository,$I_dog);
    }
}
```
To Do
=====
- add option to display a page on delete
- add option to require page confirm prior to delete
- add option to require alert confirm  prior to delete
- add option to disable listing all entities
- add function to call to check if user is allowed to view/edit/delete entity
- add events trigger on pre/post insert,delete, update
- add action to display details about an entity
