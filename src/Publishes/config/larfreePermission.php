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

        'user' => \App\Models\Common\CommonUser::class,
        //guard

    ],
    'table_names' => [
        'user' => 'common_user',
    ],
    'guard' => [
        'admin' => 'api',
    ],
    //超级管理员只能是一个人
    'super_admin' => env('SUPER_ADMIN_ID', '1,2'),

];
