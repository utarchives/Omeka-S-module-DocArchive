<?php
namespace DocArchive;

use Omeka;

return [
    'api_adapters' => [
        'invokables' => [
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            OMEKA_PATH.'/modules/DocArchive/view',
        ],
    ],
//     'entity_manager' => [
//         'mapping_classes_paths' => [
//             dirname(__DIR__) . '/src/Entity',
//         ],
// //         'proxy_paths' => [
// //             dirname(__DIR__) . '/data/doctrine-proxies',
// //         ],
//     ],
    'translator' => [
        'translation_file_patterns' => [
            [
                'type' => 'gettext',
                'base_dir' => OMEKA_PATH . '/modules/DocArchive/language',
                'pattern' => '%s.mo',
                'text_domain' => null,
            ],
        ],
    ],
    'controllers' => [
        'invokables' => [
        ],
        'factories' => [
            'DocArchive\Controller\Site\Index' => Service\Controller\Site\IndexControllerFactory::class,
        ],
    ],
    'block_layouts' => [
        'invokables' => [
        ],
        'factories' => [
            'keywordSearch' => Service\BlockLayout\KeywordSearchFactory::class,
        ],
    ],
    'form_elements' => [
        'factories' => [
            Form\ConfigForm::class => Service\Form\ConfigFormFactory::class,
        ],
    ],
    'navigation_links' => [
        'invokables' => [
            'DocArchive' => Site\Navigation\Link\DocArchiveBrowse::class,
        ],
    ],
    'view_helpers' => [
        'invokables' => [
            'parentItem' => View\Helper\ParentItem::class,
            'itemTitleWithRuby' => View\Helper\ItemTitleWithRuby::class,
            'listContent' => View\Helper\ListContent::class,
            'sortValueHeader' => View\Helper\SortValueHeader::class,
            'propertyValue' => View\Helper\PropertyValue::class,
        ],
        'factories' => [
            'listItemTitle' => Service\ViewHelper\ListItemTitleFactory::class,
            'mediaLink' => Service\ViewHelper\MediaLinkFactory::class,
            'getBgColor' => Service\ViewHelper\GetBgColorFactory::class,
            'getFolderClass' => Service\ViewHelper\GetFolderClassFactory::class,
            'docarchiveNavigation' => Service\ViewHelper\DocArchiveNavigationFactory::class,
            'getDocArchiveIdentifier' => Service\ViewHelper\GetDocArchiveIdentifierFactory::class,
        ]
    ],
    'navigation' => [
//         'AdminModule' => [
//             [
//                 'label' => 'Special Character Search',
//                 'route' => 'admin/special-character-search',
//                 'resource' => 'ResourceTree\Controller\Admin\Index',
//                 'pages' => [
//                     [
//                         'label'      => 'Character Map Import', // @translate
//                         'route'      => 'admin/special-character-search/map-import',
//                         'controller' => 'Index',
//                         'action' => 'map-import',
//                         'resource' => 'ResourceTree\Controller\Admin\Index',
//                     ],
//                 ],
//             ],
//         ],
    ],
    'router' => [
        'routes' => [
            'site' => [
                'child_routes' => [
                    'docarchive' => [
                        'type' => 'Literal',
                        'options' => [
                            'route' => '/docarchive',
                            'defaults' => [
                                '__NAMESPACE__' => 'DocArchive\Controller\Site',
                                'controller' => 'Index',
                                'action' => 'browse',
                            ],
                        ],
                    ],
                    'docarchive-identifier' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/docarchive/:identifier[/:action]',
                            'defaults' => [
                                '__NAMESPACE__' => 'DocArchive\Controller\Site',
                                'controller' => 'Index',
                                'action' => 'show',
                            ],
                            'constraints' => [
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'identifier' => '[a-zA-Z0-9_-]*',
                            ],
                        ],
                    ],
                    'searchchildren-identifier' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/search-children/:identifier',
                            'defaults' => [
                                '__NAMESPACE__' => 'DocArchive\Controller\Site',
                                'controller' => 'Index',
                                'action' => 'search-children',
                            ],
                            'constraints' => [
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'identifier' => '[a-zA-Z0-9_-]*',
                            ],
                        ],
                    ],
                    'docarchive-id' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/docarchive/:id[/:action]',
                            'defaults' => [
                                '__NAMESPACE__' => 'DocArchive\Controller\Site',
                                'controller' => 'Index',
                                'action' => 'show',
                            ],
                            'constraints' => [
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id' => '\d+',
                            ],
                        ],
                    ],
                    'searchchildren-id' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/search-children/:id',
                            'defaults' => [
                                '__NAMESPACE__' => 'DocArchive\Controller\Site',
                                'controller' => 'Index',
                                'action' => 'search-children',
                            ],
                            'constraints' => [
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id' => '\d+',
                            ],
                        ],
                    ],

//                     'search-parents' => [
//                         'type' => 'Literal',
//                         'options' => [
//                             'route' => '/search-parents',
//                             'defaults' => [
//                                 '__NAMESPACE__' => 'DocArchive\Controller\Site',
//                                 'controller' => 'Index',
//                                 'action' => 'search-parents',
//                             ],
//                         ],
//                     ],
                ],
            ],
            'admin' => [
//                 'child_routes' => [
//                     'special-character-search' => [
//                         'type' => 'Literal',
//                         'options' => [
//                             'route' => '/special-character-search',
//                             'defaults' => [
//                                 '__NAMESPACE__' => 'DocArchive\Controller\Admin',
//                                 'controller' => 'Index',
//                                 'action' => 'index',
//                             ],
//                         ],
//                         'may_terminate' => true,
//                         'child_routes' => [
//                             'map-import' => [
//                                 'type' => 'Literal',
//                                 'options' => [
//                                     'route' => '/map-import',
//                                     'defaults' => [
//                                         '__NAMESPACE__' => 'DocArchive\Controller\Admin',
//                                         'controller' => 'Index',
//                                         'action' => 'map-import',
//                                     ],
//                                 ],
//                             ],
//                         ],
//                     ],
//                 ],
            ],
        ],
    ],
    'docarchive' => [
        'settings' => [
            'docarchive_default_search' => false,
            'docarchive_default_sort' => '1',
//             'docarchive_use_parent_item_sort' => false,
            'docarchive_search_folder' => false,
        ],
    ],
];
