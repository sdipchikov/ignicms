<?php

return [
    'user' => [
        'name' => 'Team',
        'link' => '#',
        'isActive' => false,
        'iconClass' => 'fa-users',
        'permissionsNeeded' => 'manage_users',
        'subMenu' => [
            'users_manager' => [
                'name' => 'Users Manager',
                'link' => 'user.index',
                'isActive' => false,
                'permissionsNeeded' => 'manage_users',
            ],
            'permission' => [
                'name' => 'Permissions',
                'link' => 'permission.index',
                'isActive' => false,
                'iconClass' => 'fa-lock',
                'permissionsNeeded' => 'manage_users',
            ],
            'role' => [
                'name' => 'Roles',
                'link' => 'role.index',
                'isActive' => false,
                'iconClass' => 'fa-people',
                'permissionsNeeded' => 'manage_users',
            ],
        ],
    ],
    'seo_page' => [
        'name' => 'SEO Pages',
        'link' => 'seo_page.index',
        'isActive' => false,
        'iconClass' => 'fa-tags',
        'permissionsNeeded' => 'manage_users',
    ],

];
