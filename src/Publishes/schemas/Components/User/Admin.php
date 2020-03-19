<?php
/**
 * 其他可以用组建默认的参数
 * 也可以自己指定
 */
return [
    'detail' => [
        'table' => [
            'search' => [
                'name',
                'user_id',
                'roles',
                'state',
            ],
            'fields' => [
                'id',
                'name',
                'user_id',
                'roles',
                'state',
                'created_at',
            ],
        ],
        'add' => [
            'fields' => [
                'name',
                'user_id'=>[
                    'tip'=>'可不填,会创建新用户',
                ],
                'email'=>[
                    'name'=>'邮箱',
                    'type'=>'email',
                    'tip'=>'用于登录',
                ],
                'password'=>[
                    'name'=>'密码',
                    'type'=>'password',
                    'require'=>'require',
                    'tip'=>'此密码和前台用户密码通用'
                ],
                'roles',
                'comment',
                'state'
            ],
        ],
        'edit' => [
            'fields' => [
                'name',
                'user_id',
                'user.email'=>[
                    'name'=>'修改邮箱',
                    'type'=>'email',
                    'tip'=>'用于登录',
                ],
                'user.password'=>[
                    'name'=>'密码',
                    'type'=>'password',
                    'require'=>'require',
                    'tip'=>'此密码和前台用户密码通用'
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
