<?php
namespace DocArchive\Service\ViewHelper;

use DocArchive\View\Helper\DocArchiveNavigation;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class DocArchiveNavigationFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $serviceLocator, $requestedName, array $options = null)
    {
        return new DocArchiveNavigation(
            $serviceLocator
            );
    }
}