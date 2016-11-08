<?php
$config = [
    'AuthPermissions' => [
        'publicActions' => [
            'Pages' => "*",
            'Products' => "*",
            'Users' => [
                'login',
                'register',
                'reset',
//                'forget',
            ],
            'Tidings' => "*"
        ],
        'commonAuthAccess' => [
            'Pages'  => "*",
            'Users'  => "*",
        ],
        'authGroups' => [
            'admin' => [
                'group_id' => 1,
                'accesses' => '*'
            ],
            'client' => [
                'group_id' => 2,
                'accesses' => []
            ],
            'user' => [
                'group_id' => 3,
                'accesses' => []
            ]
        ]
    ]
];