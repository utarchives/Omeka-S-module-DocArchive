<?php
namespace DocArchive\View\Helper;

use Omeka\Api\Representation\AbstractResourceEntityRepresentation;
use Laminas\View\Helper\AbstractHelper;

class ParentItem extends AbstractHelper
{
    public function __invoke(AbstractResourceEntityRepresentation $resource, $newParent)
    {
        if (!$newParent) {
            return;
        }
        $targetProperty = 'dcndl:callNumber';
        $outputHtml = <<<'HTML'
<div class="item-list-header">
<table class="list-header">
    <tr>
        <td class="item-group">&nbsp;</td>
        <td class="item-list-title"><a class="parent-item-link" href="%s"><i class="fa fa-folder-open-o" aria-hidden="true"></i>&nbsp;%s</a>%s<span class="period">%s</span></td>
    </tr>
</table>
</div>
HTML;
        $referenceCode = '';
        if (isset($resource->values()[$targetProperty])) {
            $referenceCode .= '&nbsp;&nbsp;【参照コード】';
            foreach ($resource->values()[$targetProperty]['values'] as $value) {
                $referenceCode .= $value->value();
            }
        }
        $targetProperty = 'archiveshub:dateCreatedAccumulatedString';
        $period = '';
        if (isset($resource->values()[$targetProperty])) {
            $period .= '&nbsp;&nbsp;【年代域】';
            foreach ($resource->values()[$targetProperty]['values'] as $value) {
                $period .= $value->value();
            }
        }
        $view = $this->getView();
//         $url = $view->url(
//             'site/docarchive-id',
//             [
//                 'id' => $resource->id(),
//             ],
//             [],
//             true
//             );
        $url = $resource->siteUrl();
        $path = explode('/', $url);
        $targetPath = $path[count($path) - 1];
        if (strcmp($targetPath, strval($resource->id())) != 0) {
            $url = str_replace('/' . $targetPath, '', $url);
        }
        $outputHtml = sprintf($outputHtml, $url, $resource->displayTitle(), $referenceCode, $period);
        echo $outputHtml;
    }
}

