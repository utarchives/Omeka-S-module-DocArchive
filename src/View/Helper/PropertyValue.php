<?php
namespace DocArchive\View\Helper;
use SpecialCharacterSearch\Api\Representation\AbstractSearchItemRepresentation;
use Laminas\View\Helper\AbstractHelper;

class PropertyValue extends AbstractHelper
{
    public function __invoke(AbstractSearchItemRepresentation $resource, $targetProperty, $label)
    {
        $propertyValue = '';
        if (isset($resource->values()[$targetProperty])) {
            $propertyValue .= $label;
            foreach ($resource->values()[$targetProperty]['values'] as $value) {
                $propertyValue .= $value->value();
            }
        }
        return $propertyValue;
    }
}