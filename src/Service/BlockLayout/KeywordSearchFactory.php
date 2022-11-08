<?php
namespace DocArchive\Service\BlockLayout;

use Interop\Container\ContainerInterface;
use DocArchive\Site\BlockLayout\KeywordSearch;
use Laminas\ServiceManager\Factory\FactoryInterface;

class KeywordSearchFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $serviceLocator, $requestedName, array $options = null)
    {
        $defaultSiteId = $serviceLocator->get('Omeka\Settings')->get('default_site');
        $api = $serviceLocator->get('Omeka\ApiManager');
        if ($defaultSiteId) {
            $slugs = $api->search('sites', ['id' => $defaultSiteId], ['returnScalar' => 'slug'])->getContent();
        } else {
            $slugs = $api->search('sites', ['limit' => 1], ['returnScalar' => 'slug'])->getContent();
        }
        $defaultSiteSlug = (string) reset($slugs);
        return new KeywordSearch($defaultSiteSlug);
    }
}
