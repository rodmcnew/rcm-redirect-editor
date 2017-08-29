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

    /* controllers */
    'controllers' => [
        'invokables' => [
            RcmRedirectEditor\Controller\RedirectController::class =>
                RcmRedirectEditor\Controller\RedirectController::class,
            RcmRedirectEditor\ApiController\RedirectController::class =>
                RcmRedirectEditor\ApiController\RedirectController::class,
        ],
    ],

    /* navigation */
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

    /* router */
    'router' => [
        'routes' => [
            '/redirect' => [
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => [
                    'route' => '/redirect',
                    'defaults' => [
                        'controller' =>
                            RcmRedirectEditor\Controller\RedirectController::class,
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
                            RcmRedirectEditor\ApiController\RedirectController::class,
                    ]
                ],
            ],
        ],
    ],

    /* service_manager */
    'service_manager' => [
        'factories' => [
            \RcmRedirectEditor\Api\Acl\IsAllowed::class
            => \RcmRedirectEditor\Api\Acl\IsAllowedRcmUserSitesAdminFactory::class,

            \RcmRedirectEditor\Api\User\GetCurrentUserId::class
            => \RcmRedirectEditor\Api\User\GetCurrentUserIdRcmUserFactory::class,
        ],
    ],

    /* view_manager */
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
        'strategies' => [
            'ViewJsonStrategy',
        ],
    ],
];
