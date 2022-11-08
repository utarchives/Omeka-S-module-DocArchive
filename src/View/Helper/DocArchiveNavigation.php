<?php
namespace DocArchive\View\Helper;
use Omeka\Api\Representation\SiteRepresentation;
use Omeka\Site\Navigation\Link\LinkInterface;
use Laminas\Navigation\Service\ConstructedNavigationFactory;
use Laminas\View\Helper\AbstractHelper;
use Interop\Container\ContainerInterface;

class DocArchiveNavigation extends AbstractHelper
{
    protected $serviceLocator;
    protected $linkManager;
    public function __construct(ContainerInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        $this->linkManager = $serviceLocator->get('Omeka\Site\NavigationLinkManager');
    }
    public function __invoke(SiteRepresentation $site)
    {
        // Build a new Navigation helper so these changes don't leak around to other places,
        // then set it to always disable translation for any of its "child" helpers (menu,
        // breadcrumb, etc.)
        $helper = $this->serviceLocator->get('ViewHelperManager')->build('Navigation');
        $helper->getPluginManager()->addInitializer(function ($container, $plugin) {
            $plugin->setTranslatorEnabled(false);
        });
            $nav = $helper($this->getPublicNavContainer($site));
        return $nav;
    }

    /**
     * Get the navigation container for this site's public nav
     *
     * @return \Laminas\Navigation\Navigation
     */
    protected function getPublicNavContainer($site)
    {
        $services = $this->serviceLocator;
        $factory = new ConstructedNavigationFactory($this->toZend($site));
        return $factory($this->serviceLocator, '');
    }
    /**
     * Translate Omeka site navigation to Zend Navigation format.
     *
     * @param SiteRepresentation $site
     * @return array
     */
    public function toZend(SiteRepresentation $site)
    {
        $manager = $this->linkManager;
        $buildLinks = function ($linksIn) use (&$buildLinks, $site, $manager) {
            $linksOut = [];
            $count = 0;
            foreach ($linksIn as $key => $data) {
                $count++;
                if ($count == 1) {
                    continue;
                }
                $linkType = $manager->get($data['type']);
                $linkData = $data['data'];
                $linksOut[$key] = $linkType->toZend($linkData, $site);
                $linksOut[$key]['label'] = $this->getLinkLabel($linkType, $linkData, $site);
                if (isset($data['links'])) {
                    $linksOut[$key]['pages'] = $buildLinks($data['links']);
                }
            }
            return $linksOut;
        };
        $links = $buildLinks($site->navigation());
        if (!$links) {
            // The site must have at least one page for navigation to work.
            $links = [[
                'label' => 'Home',
                'route' => 'site',
                'params' => [
                    'site-slug' => $site->slug(),
                ],
            ]];
        }
        return $links;
    }
    /**
     * Get the label for a link.
     *
     * User-provided labels should be used as-is, while system-provided "backup" labels
     * should be translated.
     *
     * @param LinkInterface $link
     * @return string
     */
    public function getLinkLabel(LinkInterface $linkType, array $data, SiteRepresentation $site)
    {
        $label = $linkType->getLabel($data, $site);
        if ($label) {
            return $label;
        }
        return $this->i18n->translate($linkType->getName());
    }
}

