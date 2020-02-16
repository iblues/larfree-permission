<?php
/**
 * Created by PhpStorm.
 * User: lanyang
 * Date: 2019/12/12
 * Time: 4:28 PM
 */

return [


    'models' => [
        //管理模型
        'userAdmin' => LarfreePermission\Models\User\UserAdmin::class,
        //菜单模型
        'adminNav' => Larfree\Models\Admin\AdminNav::class,
        //具体权限模式
        'permission' => LarfreePermission\Models\Permission\PermissionPermissions::class,
        //角色模型
        'role' => LarfreePermission\Models\Permission\PermissionRoles::class,

    ]
];
