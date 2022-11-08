<?php

namespace DocArchive\Form;
use Laminas\Form\Fieldset;
use Laminas\Form\Form;
use Laminas\Form\Element\Checkbox;
use Laminas\I18n\Translator\TranslatorAwareInterface;
use Laminas\I18n\Translator\TranslatorAwareTrait;
use Omeka\Form\Element\PropertySelect;

class ConfigForm extends Form  implements TranslatorAwareInterface
{
    use TranslatorAwareTrait;
    public function init()
    {
        $this->add([
            'name' => 'docarchive_config',
            'type' => Fieldset::class,
            'options' => [
                'label' => 'DocArchive Config', // @translate
                'info' => $this->translate('Configuration for using DocArchive.')
            ],
        ]);
        $docarchiveConfigFieldSet = $this->get('docarchive_config');
        $docarchiveConfigFieldSet->add([
            'name' => 'docarchive_default_search',
            'type' => Checkbox::class,
            'options' => [
                'label' => 'Default Search', // @translate
                'info' => $this->translate('If checked searching item default view .') // @translate
            ],
        ]);
        $docarchiveConfigFieldSet->add([
            'name' => 'docarchive_search_folder',
            'type' => Checkbox::class,
            'options' => [
                'label' => 'Search Parent Folder Item', // @translate
                'info' => $this->translate('If checked search parent folder by keyword of items of children .') // @translate
            ],
        ]);
//         $docarchiveConfigFieldSet->add([
//             'name' => 'docarchive_use_parent_item_sort',
//             'type' => Checkbox::class,
//             'options' => [
//                 'label' => 'Parent Item Sort', // @translate
//                 'info' => $this->translate('If checked it works parent item sort  .') // @translate
//             ],
//         ]);
        $docarchiveConfigFieldSet->add([
            'name' => 'docarchive_default_sort',
            'type' => PropertySelect::class,
            'options' => [
                'label' => 'Default Sort', // @translate
                'empty_option' => 'Title', // @translate
                'info' => $this->translate('Select property of default sorting. (If use parent item sort it dose not work)') // @translate
            ],
        ]);
        $inputFilter = $this->getInputFilter();
        $docarchiveConfigFilter = $inputFilter->get('docarchive_config');
        $docarchiveConfigFilter->add([
            'name' => 'docarchive_default_search',
            'required' => false,
        ]);
        $docarchiveConfigFilter->add([
            'name' => 'docarchive_search_folder',
            'required' => false,
        ]);
//         $docarchiveConfigFilter->add([
//             'name' => 'docarchive_use_parent_item_sort',
//             'required' => false,
//         ]);
        $docarchiveConfigFilter->add([
            'name' => 'docarchive_default_sort',
            'required' => false,
        ]);
    }
    /**
     *
     * @param  $args
     * @return string
     */
    protected function translate($args)
    {
        $translator = $this->getTranslator();
        return $translator->translate($args);
    }
}