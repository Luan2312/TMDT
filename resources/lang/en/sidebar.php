<?php

return [
    'module' => [
        [
            'title' => 'Member Management',
            'icon' => 'fa fa-th-large',
            'name' => ['user'],
            'subModule' => [
                [
                    'title' => 'Member Group Management',
                    'route' => 'user/catalogue/index'
                ],
                [
                    'title' => 'Member Management',
                    'route' => 'user/index'
                ],
            ],
        ],
        [
            'title' => 'Post Management',
            'icon' => 'fa fa-file',
            'name' => ['post'],
            'subModule' => [
                [
                    'title' => 'Post Group Management',
                    'route' => 'post/catalogue/index'
                ],
                [
                    'title' => 'Post Management',
                    'route' => 'post/index'
                ],
            ],
        ],
        [
            'title' => 'General Configuration',
            'icon' => 'fa fa-file',
            'name' => ['lanugage'],
            'subModule' => [
                [
                    'title' => 'Language Management',
                    'route' => 'language/index'
                ],
            ],
        ],
    ],
];
