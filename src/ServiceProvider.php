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
            $model = config('larfreePermission.models.userAdmin');
            $admin = $model::where('user_id', $user->id)->first();
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

        $path = dirname(__DIR__) . '/src';

        //数据库
        $this->loadMigrationsFrom($path . '/Database/migrations');

        //配置
        $this->mergeConfigFrom(
            $path . '/Publishes/config/larfreePermission.php', 'larfreePermission'
        );


        //路由
        $this->loadRoutesFrom($path . '/Routes/api.php');
        $this->publishes([
            $path . '/Publishes/Schemas' => schemas_path(),
        ], 'larfree-permission');
    }

}
