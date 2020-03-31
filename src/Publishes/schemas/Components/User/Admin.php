<?php
/**
 * 其他可以用组建默认的参数
 * 也可以自己指定
 */
return [
    'detail' => [
        'table' => [
            'config' => [
                'action' => [
                    'password' => [
                        'type' => 'primary',
                        'html' => '密码',
                        'action' => '/',
                        'url' => '/dialog/edit/common.user/{{user_id}}/password',
                        'api' => 'PUT:///common.user',
                    ],
                ],
            ],
            'search' => [
                'name',
                'user.phone' => [
                    'name' => '手机号',
                    'type' => 'phone',
                ],
                'user.email' => [
                    'name' => '邮箱',
                    'type' => 'email',
                ],
                'roles',
                'state',
            ],
            'fields' => [
                'id',
                'name',
                'user.phone' => [
                    'name' => '手机号',
                    'type' => 'phone',
                ],
                'user.email' => [
                    'name' => '邮箱',
                    'type' => 'email',
                ],
                'roles',
                'state',
                'created_at',
            ],
        ],
        'add' => [
            'fields' => [
                'name',
                'user.email' => [
                    'name' => '邮箱',
                    'type' => 'email',
                    'tip' => '用于登录',
                ],
                'user.phone' => [
                    'name' => '手机号',
                    'type' => 'email',
                    'tip' => '会优先以手机号去匹配用户',
                ],
                'user.password' => [
                    'name' => '密码',
                    'type' => 'password',
                    'require' => 'require',
                    'tip' => '此密码和前台用户密码通用'
                ],
                'roles',
                'comment',
                'state'
            ],
        ],
        'edit' => [
            'fields' => [
                'name',
                'user_id' => [
                    'tip' => '邮箱/手机号请前往账号中心修改',
                ],
                'roles',
                'comment',
                'state'
            ],
        ],
        'detail' => [
            'fields' => [
                'id',
                'name',
                'user_id',
                'roles',
                'comment',
                'state',
                'created_at',
            ],
        ],
    ],
];
