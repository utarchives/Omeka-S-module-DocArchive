<?php
namespace DocArchive\Site\BlockLayout;

use Omeka\Api\Representation\SiteRepresentation;
use Omeka\Api\Representation\SitePageRepresentation;
use Omeka\Api\Representation\SitePageBlockRepresentation;
use Laminas\View\Renderer\PhpRenderer;
use Omeka\Site\BlockLayout\AbstractBlockLayout;
use Laminas\Form\Element;
use Laminas\Form\Form;

class KeywordSearch extends AbstractBlockLayout
{
    protected $defaultSiteSlug;
    public function __construct($defaultSiteSlug)
    {
        $this->defaultSiteSlug = $defaultSiteSlug;
    }

    public function getLabel()
    {
        return 'DocArchive Keyword Search'; // @translate
    }

    public function form(PhpRenderer $view, SiteRepresentation $site,
        SitePageRepresentation $page = null, SitePageBlockRepresentation $block = null
    ) {
        $defaults = [
            'text_align' => 'text-left',
        ];
        $data = $block ? $block->data() + $defaults : $defaults;
        $form = new Form();
        $form->add([
            'name' => 'o:block[__blockIndex__][o:data][text_align]',
            'type' => Element\Select::class,
            'options' => [
                'label' => '配置', // @translate
                'value_options' => [
                    'text-left' => '左',  // @translate
                    'text-center' => '中央',  // @translate
                    'text-right' => '右',  // @translate
                ],
            ],
            'attributes' => [
                'class' => 'browse-preview-resource-type',
            ],
        ]);
        $form->setData([
            'o:block[__blockIndex__][o:data][text_align]' => $data['text_align'],
        ]);

        return $view->formCollection($form);
    }

    public function render(PhpRenderer $view, SitePageBlockRepresentation $block)
    {
        $textAlign = $block->dataValue('text_align');
        if (!$textAlign) {
            return '';
        }
        return $view->partial('common/block-layout/keyword-search-form', ['align' => $textAlign, 'defaultSiteSlug' => $this->defaultSiteSlug]);
    }
}
