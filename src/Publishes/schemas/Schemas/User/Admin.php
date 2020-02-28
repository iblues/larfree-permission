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
            'tip' => '',
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
                'field' => ['id', 'name'],
                'select' => ['id', 'name'],//后期作废
            ],
            'component_param'=>[
                'key'=>'id',
                'name'=>'{{name}}'
            ],
        ],
        'roles' => [
            'name' => '角色',
            'tip' => '',
            'type' => 'select',
            'link' => [],
            'multi'=>true,
            'component_param'=>[
                'key'=>'id',
                'name'=>'{{name}}',
                'api'=>'/permission/roles'//指定api地址.
            ],
        ],
        'comment' => [
            'name' => '备注',
            'tip' => '',
            'type' => 'text',
            'sql_type' => 'text',
        ],
        'status' => [
            'name' => '开启',
            'tip' => '',
            'type' => 'checkbox',
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
