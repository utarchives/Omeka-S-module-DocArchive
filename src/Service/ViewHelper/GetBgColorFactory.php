<?php
namespace DocArchive\Service\ViewHelper;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use DocArchive\View\Helper\GetBgColor;


class GetBgColorFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $serviceLocator, $requestedName, array $options = null)
    {
        $controllerPlugins = $serviceLocator->get('ControllerPluginManager');
        return new GetBgColor(
            $controllerPlugins->get('relatedItemsData')
            );
    }
}