<?php
namespace DocArchive\Service\ViewHelper;

use Interop\Container\ContainerInterface;
use Omeka\Module\Manager;
use Laminas\ServiceManager\Factory\FactoryInterface;
use DocArchive\View\Helper\GetDocArchiveIndentifier;

class GetDocArchiveIdentifierFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $serviceLocator, $requestedName, array $options = null)
    {
        $settings = $serviceLocator->get('Omeka\Settings');
        $omekaModules = $serviceLocator->get('Omeka\ModuleManager');
        $module = $omekaModules->getModule('CleanUrl');
        $targetProperty = '';
        if (!$module || Manager::STATE_ACTIVE != $module->getState()) {
            $targetProperty = '';
        } else {
            $propertyId = $settings->get('cleanurl_item')['property'];
            $api = $serviceLocator->get('Omeka\ApiManager');
            $response = $api->read('properties', ['id' => $propertyId]);
            $property = $response->getContent();
            $targetProperty = $property->vocabulary()->prefix() . ':' . $property->localName();
        }
        return new GetDocArchiveIndentifier(
            $targetProperty
            );
    }
}