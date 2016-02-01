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
        ],
    ],

    /* */
    'controllers' => [
        'invokables' => [
            'RcmRedirectEditor\Controller\RedirectController' => 'RcmRedirectEditor\Controller\RedirectController',
            'RcmRedirectEditor\ApiController\RedirectController' => 'RcmRedirectEditor\ApiController\RedirectController',
            'RcmAdmin\Controller\ApiAdminManageSitesController' => 'RcmAdmin\Controller\ApiAdminManageSitesController',
        ],
    ],
    /* */
//    'doctrine' => [
//        'driver' => [
//            'Redirect' => [
//                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
//                'cache' => 'array',
//                'paths' => [
//                    __DIR__ . '/../src/Entity'
//                ]
//            ],
//            'orm_default' => [
//                'drivers' => [
//                    'Redirect' => 'Redirect'
//                ]
//            ]
//        ]
//    ],

    'router' => [
        'routes' => [
            '/redirect' => [
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => [
                    'route' => '/redirect',
                    'defaults' => [
                        'controller' => 'RcmRedirectEditor\Controller\RedirectController',
                        'action' => 'index',
                    ],
                ]
            ],
            '/api/redirect' => [
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => [
                    'route' => '/api/redirect[/:id]',
                    'defaults' => [
                        'controller' => 'RcmRedirectEditor\ApiController\RedirectController',
                    ]
                ],
            ],
        ],
    ],

    /* */
    'service_manager' => [
        'config_factories' => [
            /* Services - placeholder for format to use with redirect*/
            'RcmRedirectEditor\Service\RedirectService' => [
                'arguments' => [
                    'Doctrine\ORM\EntityManager',
                ]
            ],
        ],
    ],
////        'factories' => [
////            'Reliv\Conference\Repository\ConferenceRepository'
////            => 'Reliv\Conference\Factory\ConferenceRepositoryFactory',
////            'Reliv\Conference\SkuValidator\SkusValidator'
////            => 'Reliv\Conference\Factory\SkusValidatorFactory',
////        ]
//    ],
    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
        'strategies' => [
            'ViewJsonStrategy',
        ],
    ],
];
