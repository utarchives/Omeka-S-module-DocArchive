<?php
namespace DocArchive\View\Helper;

use Omeka\Api\Representation\AbstractResourceEntityRepresentation;
use Laminas\View\HelperPluginManager;
use Laminas\View\Helper\AbstractHelper;

class MediaLink extends AbstractHelper
{
    protected $viewHelper;
    public function __construct(HelperPluginManager $viewHelper) {
        $this->viewHelper = $viewHelper;
    }
    public function __invoke(AbstractResourceEntityRepresentation $resource, $thumbnailType = 'square', $attributes)
    {
        $escape = $this->viewHelper->get('escapeHtml');
        $thumbnail = $this->viewHelper->get('thumbnail');
        $linkContent =  $thumbnail($resource, $thumbnailType);
        if (empty($attributes['class'])) {
            $attributes['class'] = 'resource-link';
        } else {
            $attributes['class'] .= ' resource-link';
        }
        return $resource->linkRaw($linkContent, null, $attributes);
    }
}

