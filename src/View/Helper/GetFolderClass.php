<?php
namespace DocArchive\View\Helper;

use ResourceTree\Mvc\Controller\Plugin\RelatedItemsData;
use SpecialCharacterSearch\Api\Representation\AbstractSearchItemRepresentation;
use Laminas\View\Helper\AbstractHelper;

class GetFolderClass extends AbstractHelper
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
        if (strcmp($resource->resourceClass()->id(), $this->relatedItemsData->getSearchFolderResourceClassId()) == 0) {
            return 1;
        } else if (strcmp($resource->resourceClass()->id(), $this->relatedItemsData->getFolderResourceClassId()) == 0) {
            return 2;
        } else {
            return 3;
        }

    }
}

