<?php

namespace MvaCrud\Listener;

use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\Mvc\MvcEvent;

class ModuleRouteListener implements ListenerAggregateInterface
{
    const MODULE_NAMESPACE    = '__NAMESPACE__';
    const ORIGINAL_CONTROLLER = '__CONTROLLER__';

    /**
     * @var \Zend\Stdlib\CallbackHandler[]
     */
    protected $listeners = array();

    /**
     * Attach to an event manager
     *
     * @param  EventManagerInterface $events
     * @param  integer $priority
     */
    public function attach(EventManagerInterface $events, $priority = 1)
    {
        
        $this->listeners[] = $events->attach(MvcEvent::EVENT_ROUTE, array($this, 'onRoute'), $priority);
    }

    /**
     * Detach all our listeners from the event manager
     *
     * @param  EventManagerInterface $events
     * @return void
     */
    public function detach(EventManagerInterface $events)
    {
        foreach ($this->listeners as $index => $listener) {
            if ($events->detach($listener)) {
                unset($this->listeners[$index]);
            }
        }
    }

    /**
     * Listen to the "route" event and determine if the module namespace should
     * be prepended to the controller name.
     *
     * If the route match contains a parameter key matching the MODULE_NAMESPACE
     * constant, that value will be prepended, with a namespace separator, to
     * the matched controller parameter.
     *
     * @param  MvcEvent $e
     * @return null
     */
    public function onRoute(MvcEvent $e)
    {
        
        $matches = $e->getRouteMatch();
        
        
        if (!$matches instanceof \Zend\Mvc\Router\Http\RouteMatch) {
            // Can't do anything without a route match
            //return;
        }
       
        $moduleManager =  $e->getApplication()->getServiceManager()->get('modulemanager');
        $modules = array_keys($moduleManager->getLoadedModules(false));
        
        $module = $matches->getParam('module', false);
        str_replace(' ', '', ucwords(str_replace('-', ' ', $module)));
        
        if (!$module || !in_array(str_replace(' ', '', ucwords(str_replace('-', ' ', $module))), $modules)) {
            // No module found or not in the enabled module list; nothing to do
            return;
        }

        $controller = $matches->getParam('controller', false);
        if (!$controller) {
            // no controller matched, nothing to do
            return;
        }

        // Ensure the module namespace has not already been applied
        if (0 === strpos($controller, $module)) {
            return;
        }

        // Keep the originally matched controller name around
        $matches->setParam(self::ORIGINAL_CONTROLLER, $controller);

        // Prepend the controllername with the module and namespace, and replace it in the
        // matches
        $controller = str_replace(' ', '', ucwords(str_replace('-', ' ', $module))) . '\\Controller\\' . str_replace(' ', '', ucwords(str_replace('-', ' ', $controller)));
                
        $matches->setParam('controller', $controller);
    }
}