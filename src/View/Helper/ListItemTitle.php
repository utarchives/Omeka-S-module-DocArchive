<?php
namespace DocArchive\View\Helper;

use ResourceTree\Mvc\Controller\Plugin\RelatedItemsData;
use SpecialCharacterSearch\Api\Representation\AbstractSearchItemRepresentation;
use Laminas\View\Helper\AbstractHelper;

class ListItemTitle extends AbstractHelper
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

        $targetProperty = 'dcndl:callNumber';
        $outputHtml =  '<a href="%s" class="child-item-link">';
        if ($resource->resourceClass()->id() == $this->relatedItemsData->getDocumentResourceClassId()) {
            $outputHtml =  '<a href="%s" class="child-item-link" target="_blank">';
        }
        if ($resource->resourceClass()->id() == $this->relatedItemsData->getDocumentResourceClassId()) {
            $outputHtml .= '<i class="fa fa-file" aria-hidden="true" style="color:#3c76b5" ></i>';
        } else {
            $outputHtml .= '<i class="fa fa-folder-open" aria-hidden="true" style="color:#daa520" ></i>';
        };
        $outputHtml .= '&nbsp;%s</a>';
//         $referenceCode = '';
//         if (isset($resource->values()[$targetProperty])) {
//             $referenceCode .= '&nbsp;&nbsp;【参照コード】';
//             foreach ($resource->values()[$targetProperty]['values'] as $value) {
//                 $referenceCode .= $value->value();
//             }
//         }
        $url = $resource->siteUrl();
//         if ($resource->resourceClass()->id() == $this->relatedItemsData->getFolderResourceClassId() ||
//             $resource->resourceClass()->id() == $this->relatedItemsData->getSearchFolderResourceClassId()) {
//             $url = $resource->listUrl();
//         }
        $outputHtml = sprintf($outputHtml, $url, $resource->displayTitle());
//         if ($parent) {
//             $targetProperty = 'archiveshub:dateCreatedAccumulatedString';
//             if (isset($resource->values()[$targetProperty])) {
//                 $outputHtml .= '<span class="period">&nbsp;&nbsp;【年代域】';
//                 $period = '';
//                 foreach ($resource->values()[$targetProperty]['values'] as $value) {
//                     $period .= $value->value();
//                 }
//                 $outputHtml .= $period;
//                 $outputHtml .= '</span>';
//             }
//         }

        echo $outputHtml;
    }
}

