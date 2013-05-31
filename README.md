MvaCrud
========
Base module to extend to simplify crud development.

Installation
============
## Composer

The suggested installation method is via [composer](http://getcomposer.org/):

```sh
php composer.phar require rinomau/MvaCrud:dev-master
```
## Git Submodule

 1. Install [MvaCrud](https://github.com/rinomau/MvaCrud.git)
 2. Clone this project into your `./vendor/` directory

    ```sh
    cd vendor
    git clone https://github.com/rinomau/MvaCrud.git
    ```

Configuration
=============
## Global configuration
Copy `./vendor/rinomau/config/MvaCrud.config.php` to `./config/autoload/MvaCrud.config.php`
This configuration parameters applies to all modules that use MvaCrud

## Per module configuration
Add in `module/YourModule/config/config.php` a section like this

```
    'MvaCrud' => array(
        __NAMESPACE__ => array(
            // 's_indexTitle'  => 'Titolo della index per cane',
            's_indexTemplate'   => 'crud/index/index',
            's_newTitle'  => 'Titolo della new per cane',
            's_newTemplate'   => 'crud/index/default-form',
            's_editTitle'  => 'Titolo della edit per cane',
            's_editTemplate'   => 'crud/index/default-form',
        )
    )
```
This configuration parameters applies to all controller extending MvaCrud defined in that namespace

## Per controller configuration
Redefine in your controller parameters you want to edit after call MvaCrud constructor like

```
class IndexController extends \MvaCrud\Controller\CrudIndexController {
    
    public function __construct($I_service, $I_form) {
        $entityName = 'Dog';
        parent::__construct($entityName, $I_service, $I_form);
        $this->s_indexTitle = 'Title specific for this controller';
    }
}
```


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
