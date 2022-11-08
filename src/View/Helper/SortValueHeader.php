<?php
namespace DocArchive\View\Helper;

use SpecialCharacterSearch\Api\Representation\AbstractSearchItemRepresentation;
use Laminas\View\Helper\AbstractHelper;

class SortValueHeader extends AbstractHelper
{
    public function __invoke(AbstractSearchItemRepresentation $resource, $sortValue, $break)
    {
        if (!$break) {
            return;
        }
        $outputHtml = <<<'HTML'
<div class="item-list-header">
<table class="list-header">
    <tr>
        <td class="item-group">&nbsp;</td>
        <td class="item-list-title">%s</td>
    </tr>
</table>
</div>
HTML;
        $outputHtml = sprintf($outputHtml, $sortValue);
        echo $outputHtml;
    }
}

