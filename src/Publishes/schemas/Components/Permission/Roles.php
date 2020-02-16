<?php
/**
 * 其他可以用组建默认的参数
 * 也可以自己指定
 */
return [
    'detail' => [
        'table' => [
            'search'=>[
                'name',
                'guard_name',
            ],
            'fields' => [
                'id',
                'name',
                'guard_name',
                'created_at',
            ],
        ],
        'add' => [
            'fields' => [
                'name',
                'guard_name',
                'nav',
            ],
        ],
        'edit' => [
            'fields' => [
                'name',
                'guard_name',
                'nav',
            ],
        ],
        'detail' => [
            'fields' => [
                'id',
                'name',
                'guard_name',
                'created_at',
                'updated_at'
            ],
        ],
    ],
];
