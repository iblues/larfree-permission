<?php
/**
 * Created by PhpStorm.
 * User: lanyang
 * Date: 2018/9/14
 * Time: 下午5:20
 */

namespace LarfreePermission;

use App\Models\Common\CommonUser;
use Illuminate\Support\Facades\Event;
use Larfree\Models\Admin\AdminNav;
use LarfreePermission\Console\RenameApiPermission;
use LarfreePermission\Models\Permission\PermissionRoles;
use LarfreePermission\Models\User\UserAdmin;
use LarfreePermission\Services\Permission\PermissionPermissionsService;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register()
    {
    }

    public function boot()
    {
        //监听
        Event::listen('permission.*', function ($eventName, array $data) {
            //根据权限判断
            $user = \Auth::guard(config('larfreePermission.guard.admin', 'api'))->user();
            //如果没用户,先都直接返回
            if(!$user){
                return $data;
            }
            $model = config('larfreePermission.models.userAdmin');
            $admin = $model::where('user_id', $user->id)->where('state', 1)->first();
            $admin || apiError('您并无管理员权限', [], 401);

            //检查导航
            if ($eventName == 'permission.filter_nav') {
                $nav = PermissionPermissionsService::make()->checkNavPermission($data['nav'], $admin);
                return $nav;
            }

            //检查api
            if ($eventName == 'permission.filter_schemas_api') {
                return PermissionPermissionsService::make()->checkApiSchemas($data['schemas'], $admin);
            }
        });

//        Event::listen('larfree.install', function ($eventName, array $data) {
//
//        });
        Event::listen('larfree.install*', function ($eventName, array $data) {
            switch ($eventName) {
                case 'larfree.install':
                    $this->createAdminNav();
                    //创建菜单.
                    break;
                case 'larfree.install.admin':
                    $this->createAdmin($data['user']);
                    break;
            }
//            dump($data);
        });


        $path = dirname(__DIR__) . '/src';

        //数据库
        $this->loadMigrationsFrom($path . '/Database/migrations');

        //配置
        $this->mergeConfigFrom(
            $path . '/Publishes/config/larfreePermission.php', 'larfreePermission'
        );

        if ($this->app->runningInConsole()) {
            $this->commands([
                RenameApiPermission::class
            ]);
        }


        //路由
        $this->loadRoutesFrom($path . '/Routes/api.php');
        $this->publishes([
            $path . '/Publishes/Schemas' => schemas_path(),
        ], 'larfree-permission');
    }

    /**
     * 创建管理员
     * @param $user
     * @author Blues
     *
     */
    protected function createAdmin($user)
    {
        PermissionRoles::query()->firstOrCreate(
            ['id' => 1],
            ['id', 1, 'name' => 'Super Admin']
        );
        UserAdmin::query()->firstOrCreate(
            ['user_id' => $user->id],
            ['user_id' => $user->id, 'name' => 'Super Admin', ['role' => 1]]
        );
    }

    /**
     * 创建后台菜单
     * @author Blues
     *
     */
    protected function createAdminNav()
    {
        $parent = AdminNav::query()->firstOrCreate(
            ['url' => '/permission'],
            ['url' => '/permission', 'name' => '后台管理', 'state' => 1, 'component' => 'curd/table', 'class' => 'setting']
        );

        AdminNav::query()->firstOrCreate(
            ['url' => '/curd/user.admin'],
            ['url' => '/curd/user.admin', 'parent_id' => $parent->id, 'name' => '管理账号', 'state' => 1, 'component' => 'curd/table', 'class' => 'user']
        );
        AdminNav::query()->firstOrCreate(
            ['url' => '/curd/permission.roles'],
            ['url' => '/curd/permission.roles', 'parent_id' => $parent->id, 'name' => '角色设置', 'state' => 1, 'component' => 'curd/table', 'class' => 'setting']
        );
        AdminNav::query()->firstOrCreate(
            ['url' => '/curd/permission.permissions'],
            ['url' => '/curd/permission.permissions', 'parent_id' => $parent->id, 'name' => '权限明细', 'state' => 1, 'component' => 'curd/table', 'class' => 'setting']
        );

    }

}
