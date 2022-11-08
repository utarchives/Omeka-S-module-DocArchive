<?php
namespace DocArchive\Service\ViewHelper;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;
use DocArchive\View\Helper\MediaLink;

class MediaLinkFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $serviceLocator, $requestedName, array $options = null)
    {
        $viewHelperManager = $serviceLocator->get('ViewHelperManager');
        return new MediaLink(
            $viewHelperManager
            );
    }
}