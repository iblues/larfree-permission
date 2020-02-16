<?php
return [
    'detail' => [

        'id' => [
            'name' => 'id',
            'tip' => '',
            'type' => 'number',
            'sql_type' => 'bigint(20) unsigned',
        ],
        'name' => [
            'name' => '角色名',
            'tip' => '',
            'type' => 'text',
            'sql_type' => 'varchar(191)',
        ],
        'guard_name' => [
            'name' => '权限类型',
            'tip' => '',
            'type' => 'select',
            'option' => [
                'admin' => '后台权限',
            ],
            'default' => 'admin',
            'sql_type' => 'varchar(191)',
        ],

        'nav' => [
            'name' => '权限类型',
            'tip' => '',
            'type' => 'tree',
            //哪怕link没有值, 也需要存在,代表他有值
            'link' =>true,
            'component_param'=>[
                'key'=>'target_id',
                'value'=>'name',
//                'api'=>'/{$COMPONENT_API}/nav/tree',
                'api'=>'/admin/nav/tree'//指定api地址.
            ],
            'sql_type' => 'varchar(191)',
        ],

        'api' => [
            'name' => '权限类型',
            'tip' => '',
            'type' => 'select',
            //哪怕link没有值, 也需要存在,代表他有值
            'link' =>true,
            'component_param'=>[
                'key'=>'target_id',
                'value'=>'name',
//                'api'=>'/{$COMPONENT_API}/nav/tree',
                'api'=>'/admin/nav/tree'//指定api地址.
            ],
            'sql_type' => 'varchar(191)',
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
