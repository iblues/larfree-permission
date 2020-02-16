<?php
/**
 * Created by PhpStorm.
 * User: lanyang
 * Date: 2018/9/14
 * Time: 下午5:20
 */

namespace LarfreePermission;


class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function register()
    {
    }

    public function boot()
    {

        //具体怎么写
//        Event::listen(App\Events\Permission\FilterNavEvent::class, function ($eventName, array $data) {
//            dump($eventName);
//            //
//        });

        $path = dirname(__DIR__).'/src';

        //数据库
        $this->loadMigrationsFrom($path.'/Database/migrations');

        //配置
        $this->mergeConfigFrom(
            $path.'/Publishes/config/larfreePermission.php', 'larfreePermission'
        );
        $this->mergeConfigFrom(
            $path.'/Publishes/config/permission.php', 'permission'
        );

        //路由
        $this->loadRoutesFrom($path . '/Routes/api.php');
        $this->publishes([
            $path.'/Publishes/Schemas' => schemas_path(),
        ],'larfree-permission');
    }

}
