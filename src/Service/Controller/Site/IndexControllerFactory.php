<?php
namespace DocArchive\Service\Controller\Site;

use Interop\Container\ContainerInterface;
use Omeka\Module\Manager;
use DocArchive\Controller\Site\IndexController;
use Laminas\ServiceManager\Factory\FactoryInterface;

class IndexControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $serviceLocator, $requestedName, array $options = null)
    {
        $settings = $serviceLocator->get('Omeka\Settings');
        $defaultSearch = $settings->get('docarchive_default_search', false);
        $api = $serviceLocator->get('Omeka\ApiManager');
        $defaultSort = '';
        $currentTheme = $serviceLocator->get('Omeka\Site\ThemeManager')->getCurrentTheme();
        $siteSettings = $serviceLocator->get('Omeka\Settings\Site');
        $themeSettings = $siteSettings->get($currentTheme->getSettingsKey(), []);
        $sortProperties = $themeSettings['sort_property'];
        if ($sortProperties != "") {
            $rows = explode("\n", $sortProperties);
            for ($i = 0; $i < count($rows); $i++) {
                $row = $rows[$i];
                $elements = explode(",", $row);
                if (count($elements) > 1) {
                    $defaultSort = $elements[0];
                    break;
                }
            }
        }
        if ($defaultSort == "") {
            $defaultSort = 'dcterms:title';
            if ($settings->get('docarchive_default_sort')) {
                $response = $api->read('properties', ['id' => $settings->get('docarchive_default_sort')]);
                $property = $response->getContent();
                $localName = $property->localName();
                $vocabulary = $property->vocabulary()->prefix();
                $defaultSort = $vocabulary . ':' . $localName;
            }
        }
        $searchFolder = $settings->get('docarchive_search_folder', false);
//         $parentSort = $settings->get('docarchive_use_parent_item_sort', false);
        $targetProperty = '';
        $omekaModules = $serviceLocator->get('Omeka\ModuleManager');
        $module = $omekaModules->getModule('CleanUrl');
        if (!$module || Manager::STATE_ACTIVE != $module->getState()) {
            $targetProperty = '';
        } else {
            $propertyId = $settings->get('cleanurl_item')['property'];
            $api = $serviceLocator->get('Omeka\ApiManager');
            $response = $api->read('properties', ['id' => $propertyId]);
            $property = $response->getContent();
            $targetProperty = $property->vocabulary()->prefix() . ':' . $property->localName();
        }
        return new IndexController(
            $defaultSearch,
            $defaultSort,
            $searchFolder,
            $targetProperty,
//             $parentSort,
            $serviceLocator->get('ViewHelperManager')
            );
    }


}