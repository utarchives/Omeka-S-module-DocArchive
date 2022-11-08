<?php
namespace DocArchive\View\Helper;

use Omeka\Api\Representation\AbstractResourceEntityRepresentation;
use Laminas\View\Helper\AbstractHelper;

class ItemTitleWithRuby extends AbstractHelper
{
    public function __invoke(AbstractResourceEntityRepresentation $resource)
    {
        $targetProperty = 'dcndl:callNumber';
        $outputWithRuby = <<<'HTML'
<ruby><rb>%s</rb><rp>（</rp><rt>%s</rt><rp>）</rp></ruby>
HTML;
        if (isset($resource->values()['dcndl:titleTranscription'])) {
            $ruby = "";
            foreach ($resource->values()['dcndl:titleTranscription']['values'] as $value) {
                $ruby .= $value->value();
            }
            $outputHtml = sprintf($outputWithRuby, $resource->displayTitle(), $ruby);
//             $outputHtml = str_replace('$$$$$$$$$$$$$$$$', $ruby, $outputHtml);
            echo $outputHtml;
        } else {
            echo $resource->displayTitle();
        }
    }
}

