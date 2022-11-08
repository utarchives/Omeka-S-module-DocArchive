<?php
namespace DocArchive\Service\ViewHelper;

use DocArchive\View\Helper\ListItemTitle;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class ListItemTitleFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $serviceLocator, $requestedName, array $options = null)
    {
        $controllerPlugins = $serviceLocator->get('ControllerPluginManager');
        return new ListItemTitle(
            $controllerPlugins->get('relatedItemsData')
            );
    }
}