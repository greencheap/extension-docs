<?php
return [
    'name' => 'docs',

    'main' => function(){},

    'autoload' => [
        'GreenCheap\\Docs\\' => 'src'
    ],

    'menu' => [
        'docs' => [
            'priority' => 100,
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
    ]
];
?>
