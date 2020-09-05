<?php
use GreenCheap\Docs\Event\RouteListener;
return [
    'name' => 'docs',

    'main' => function(){},

    'autoload' => [
        'GreenCheap\\Docs\\' => 'src'
    ],

    'menu' => [
        'docs' => [
            'priority' => 110,
            'label' => 'Docs',
            'icon' => 'docs:icon.svg',
            'url' => '@docs/admin/post',
            'active' => '@docs/admin/post*'
        ],
        'docs: index' => [
            'parent' => 'docs',
            'priority' => 0,
            'label' => 'Docs',
            'url' => '@docs/admin/post',
            'active' => '@docs/admin/post*'
        ],
        'docs: settings' => [
            'label' => 'Settings',
            'parent' => 'docs',
            'url' => '@docs/admin/settings',
            'active' => '@docs/admin/settings*',
            'access' => 'system: access settings'
        ]
    ],

    'nodes' => [
        'docs' => [
            'name' => '@docs',
            'label' => 'Docs',
            'protected' => true,
            'frontpage' => true,
            'controller' => 'GreenCheap\\Docs\\Controller\\SiteController'
        ]
    ],

    'routes' => [
        'docs' => [
            'name' => '@docs',
            'controller' => [
                'GreenCheap\\Docs\\Controller\\DocsController',
                'GreenCheap\\Docs\\Controller\\ApiDocsController',
            ]
        ]
    ],

    'config' => [
        'posts' => [
            'posts_per_page' => 20,
            'markdown_enabled' => true
        ],
        'permalink' => [
            'type' => '',
            'custom' => '{slug}'
        ]
    ],

    'events' => [
        'boot' => function ($event, $app) {
            $app->subscribe(
                new RouteListener,
            );
        },
    ]
];
?>
