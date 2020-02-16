<?php
/**
 * 其他可以用组建默认的参数
 * 也可以自己指定
 */
return [
    'detail' => [
        'table' => [
            'fields' => [
                'id',
                'name',
                'user_id',
                'status',
                'created_at',
            ],
        ],
        'add' => [
            'fields' => [
                'name',
                'user_id',
                'comment',
                'status'
            ],
        ],
        'edit' => [
            'fields' => [
                'name',
                'user_id',
                'comment',
                'status'
            ],
        ],
        'detail' => [
            'fields' => [
                'id',
                'name',
                'user_id',
                'comment',
                'status',
                'created_at',
            ],
        ],
    ],
];
