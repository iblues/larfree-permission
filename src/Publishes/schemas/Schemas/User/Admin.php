<?php
return [
    'detail' => [

        'id' => [
            'name' => 'id',
            'tip' => '',
            'type' => 'text',
            'sql_type' => 'int(10) unsigned',
        ],
        'name' => [
            'name' => '姓名',
            'tip' => '非登录账户,仅用于记录',
            'type' => 'text',
            'sql_type' => 'varchar(191)',
        ],
        'user_id' => [
            'name' => '绑定用户',
            'tip' => '',
            'type' => 'select',
            'link' => [
                'model' => [
                    'belongsTo',
                    'App\\Models\\Common\\CommonUser',
                    'user_id',
                    'id',
                ],
                'field' => ['id', 'name','email']
            ],
            'component_param' => [
                'key' => 'id',
                'name' => '{{name}} ( {{email}} )'
            ],
            'tip'=>'用于第三方登录等'
        ],
        'roles' => [
            'name' => '角色',
            'tip' => '',
            'type' => 'select',
            'link' => [],
            'multi' => true,
            'component_param' => [
                'key' => 'id',
                'name' => '{{id}}:{{name}}',
                'api' => '/permission/roles'//指定api地址.
            ],
            'tip'=>'用于权限设置',
        ],
        'comment' => [
            'name' => '备注',
            'tip' => '',
            'type' => 'text',
            'sql_type' => 'text',
        ],
        'state' => [
            'name' => '开启',
            'tip' => '',
            'type' => 'checkbox',
            'default' => 1,
            'sql_type' => 'tinyint(1)',
        ],
        'created_at' => [
            'name' => '创建日期',
            'tip' => '',
            'type' => 'timestamp',
            'sql_type' => 'timestamp',
        ],
        'updated_at' => [
            'name' => '修改日期',
            'tip' => '',
            'type' => 'timestamp',
            'sql_type' => 'timestamp',
        ],
    ],
];
