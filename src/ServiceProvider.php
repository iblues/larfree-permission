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
            if ($eventName == 'permission.filter_nav') {
                //根据权限判断
                $guard = config('larfreePermission.guard.admin', 'api');
                $user = \Auth::guard($guard)->user();
                $user || apiError('当前登录用户不存在', [], 401);

                $model = config('larfreePermission.models.userAdmin');
                $admin = $model::where('user_id', $user->id)->first();
                $admin || apiError('您并无管理员权限', [], 401);

                $nav = PermissionPermissionsService::checkNavPermission($data['nav'], $data['model'], $admin);
                $nav || apiError('无任何菜单权限', [], 401);
                return $nav;
            }
        });

        $path = dirname(__DIR__) . '/src';

        //数据库
        $this->loadMigrationsFrom($path . '/Database/migrations');

        //配置
        $this->mergeConfigFrom(
            $path . '/Publishes/config/larfreePermission.php', 'larfreePermission'
        );

        // 貌似没起效果 要检查
        $this->mergeConfigFrom(
            $path . '/Publishes/config/permission.php', 'permission'
        );

        //路由
        $this->loadRoutesFrom($path . '/Routes/api.php');
        $this->publishes([
            $path . '/Publishes/Schemas' => schemas_path(),
        ], 'larfree-permission');
    }

}
