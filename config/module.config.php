<?php
return [
    /**
     * Configuration
     */
    'asset_manager' => [
        'resolver_configs' => [
            'aliases' => [
                'modules/rcm-redirect-editor/' => __DIR__ . '/../public/',
            ],
            'collections' => [
                'modules/rcm-admin/admin.js' => [
                    'modules/rcm-redirect-editor/rcm-redirect.js',
                    'modules/rcm-redirect-editor/domain-filter.js',
                    'modules/rcm-redirect-editor/redirect-list-filter.js',
                    'modules/rcm-redirect-editor/rcm-redirect-editor.js'
                ],
                'modules/rcm/modules.css' => [
                    'modules/rcm-redirect-editor/styles.css'
                ],
            ],
        ],
    ],

    /* */
    'controllers' => [
        'invokables' => [
            'RcmRedirectEditor\Controller\RedirectController' =>
                'RcmRedirectEditor\Controller\RedirectController',
            'RcmRedirectEditor\ApiController\RedirectController' =>
                'RcmRedirectEditor\ApiController\RedirectController',
            'RcmAdmin\Controller\ApiAdminManageSitesController' =>
                'RcmAdmin\Controller\ApiAdminManageSitesController',
        ],
    ],
    'navigation' => [
        'RcmAdminMenu' => [
            'Site' => [
                'pages' => [
                    'Redirect Editor' => [
                        'label' => 'Redirect Editor',
                        'class' => '',
                        'uri' => '/redirect',
                        'title' => 'Redirect Editor',
                    ]
                ]
            ],
        ]
    ],
    'router' => [
        'routes' => [
            '/redirect' => [
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => [
                    'route' => '/redirect',
                    'defaults' => [
                        'controller' =>
                            'RcmRedirectEditor\Controller\RedirectController',
                        'action' => 'index',
                    ],
                ]
            ],
            '/api/redirect' => [
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => [
                    'route' => '/api/redirect[/:id]',
                    'defaults' => [
                        'controller' =>
                            'RcmRedirectEditor\ApiController\RedirectController',
                    ]
                ],
            ],
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
        'strategies' => [
            'ViewJsonStrategy',
        ],
    ],

];
