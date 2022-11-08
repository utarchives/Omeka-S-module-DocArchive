<?php
namespace DocArchive\View\Helper;

use ResourceTree\Mvc\Controller\Plugin\RelatedItemsData;
use SpecialCharacterSearch\Api\Representation\AbstractSearchItemRepresentation;
use Laminas\View\Helper\AbstractHelper;

class GetBgColor extends AbstractHelper
{
    /**
     *
     * @var RelatedItemsData
     */
    protected $relatedItemsData;
    public function __construct(RelatedItemsData $relatedItemsData)
    {
        $this->relatedItemsData = $relatedItemsData;
    }
    public function __invoke(AbstractSearchItemRepresentation $resource)
    {
        if (strcmp($resource->resourceClass()->id(), $this->relatedItemsData->getDocumentResourceClassId()) != 0) {
            return ' list-folder-item';
        }
    }
}

