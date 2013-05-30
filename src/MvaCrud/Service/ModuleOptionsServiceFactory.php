<?php
namespace MvaCrud\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use MvaCrud\Options\ModuleOptions;

class ModuleOptionsServiceFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     *
     * @return \Contents\Service\ContentService;
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config');
        return new ModuleOptions(isset($config['mvacrud']) ? $config['mvacrud'] : array());
    }
}
