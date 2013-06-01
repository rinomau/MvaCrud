MvaCrud
========
Base module to extend to simplify crud development.

Installation
============
## Composer

The suggested installation method is via [composer](http://getcomposer.org/):

```sh
php composer.phar require rinomau/mva-crud:dev-master
```

or

1. Add this project in your composer.json:

    ```json
    "require": {
        "rinomau/mva-crud": "dev-master"
    }
    ```

2. Now tell composer to download MvaCrud by running the command:

    ```bash
    $ php composer.phar update
    ```

## Git Submodule

 Clone this project into your `./vendor/` directory

    ```sh
    cd vendor
    git clone https://github.com/rinomau/MvaCrud.git
    ```

Configuration
=============
### Global configuration
Copy `./vendor/rinomau/mva-crud/config/MvaCrud.config.php` to `./config/autoload/MvaCrud.config.php`
This configuration parameters applies to all modules that use MvaCrud

### Per module configuration
Add in `module/YourModule/config/config.php` a section like this

```
    'MvaCrud' => array(
        __NAMESPACE__ => array(
            's_indexTitle'      => 'Index page default',
            's_indexTemplate'   => 'crud/index/index',
            's_newTitle'        => 'New page default',
            's_newTemplate'     => 'crud/index/default-form',
            's_editTitle'       => 'Edit page default',
            's_editTemplate'    => 'crud/index/default-form',
            's_detailTitle'     => 'Detail page default',
            's_detailTemplate'  => 'crud/index/detail',
            's_processErrorTitle'       => 'Form errors page default',
            's_processErrorTemplate'    => 'crud/index/default-form',
            's_deleteRouteRedirect'     => 'crud',
            's_processRouteRedirect'     => 'crud',
        )
    )
```
This configuration parameters applies to all controller extending MvaCrud defined in that namespace

### Per controller configuration
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
- allow to use doctrine annotation @HasLifecycleCallbacks and propagate exception (this can be used to check if user has rights to insert/update/delete on repository)
- allow to call a subset of entities from controller by passing an array of options to use with findBy
- use hydrator to populate entities fields
- allow to overwrite template without change configuration file, simply creating right folder structure (like zfcuser)
- add fetchAllAsArray function to Service to easy populate select options
- add fetchAsArray($options) function to Service to easy populate select options with a subset of entities
- add base form with method to set text lenght based on entity lenght
