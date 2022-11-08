<?php
namespace DocArchive\View\Helper;

use SpecialCharacterSearch\Api\Representation\AbstractSearchItemRepresentation;
use Laminas\View\Helper\AbstractHelper;

class ListContent extends AbstractHelper
{
    public function __invoke(AbstractSearchItemRepresentation $resource, $propertyList)
    {
        foreach ($propertyList as $targetProperty) {
            if (isset($resource->values()[$targetProperty])) {
                $isShow = false;
                echo '<span class="list-content-label">[' ;
                if(isset($resource->values()[$targetProperty]['alternate_label'])) {
                    echo $resource->values()[$targetProperty]['alternate_label'];
                } else {
                    echo $resource->values()[$targetProperty]['property']->label();
                }
                echo ']</span>&nbsp;';
                echo '<span class="list-content-value">';
                foreach ($resource->values()[$targetProperty]['values'] as $value) {
                    echo $value->value();
                }
                echo '</span>';
            }
        }
    }
}

