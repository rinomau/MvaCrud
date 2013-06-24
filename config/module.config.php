<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace MvaCrud;

return array(
    'router' => array(
        'routes' => array(
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /MvaModuleTemplate/:controller/:action
            'mva-crud' => array(
                'type'    => 'Segment',
                'priority' => 1000,
                'options' => array(
                    'route'    => '/:module',
                    'constraints' => array(
                        'module'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    
                    'new' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route'    => '/new',
                            'defaults' => array(
                                'action' => 'new',
                            ),
                        ),
                    ),
                    
                    'process' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route'    => '/process',
                            'defaults' => array(
                                'action' => 'process',
                            ),
                        ),
                    ),
                    
                    'edit' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route'    => '/edit[/:id]',
                            'constraints' => array(
                                'id'         => '[0-9]*',
                            ),
                            'defaults' => array(
                                'action' => 'edit',
                            ),
                        ),
                    ),
                    
                    'delete' => array(
                        'type' => 'Segment',
                        'options' => array(
                            'route'    => '/delete[/:id]',
                            'constraints' => array(
                                'id'         => '[0-9]*',
                            ),
                            'defaults' => array(
                                'action' => 'delete',
                            ),
                        ),
                    ),
                                        
                ),
            ),
        ),
    ),
    
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    
    'MvaCrud' => array(
        's_indexTitle' => 'Index page MvaCrud default',
        's_indexTemplate'   => 'mva-crud/index/index',
        's_newTitle'  => 'New page MvaCrud default',
        's_newTemplate'   => 'mva-crud/index/default-form',
        's_editTitle'  => 'Edit page MvaCrud default',
        's_editTemplate'   => 'mva-crud/index/default-form',
        's_detailTitle' => 'Detail page MvaCrud default',
        's_detailTemplate' => 'mva-crud/index/detail',
        's_processErrorTitle' => 'Error page MvaCrud default',
        's_processErrorTemplate' => 'mva-crud/index/default-form',
        's_deleteRouteRedirect' => 'mva-crud',
        's_processRouteRedirect' => 'mva-crud',
    ),
);
