<?php
/**
 * 仓库类. 所有数据交互通过此模式
 * @author blues
 */

namespace LarfreePermission\Services\Permission;

use Larfree\Services\SimpleLarfreeService;
use LarfreePermission\Models\Permission\PermissionPermissions;
use LarfreePermission\Models\User\UserAdmin;
use Spatie\Permission\Exceptions\PermissionDoesNotExist;

class PermissionPermissionsService extends SimpleLarfreeService
{
    /**
     * @var PermissionPermissions
     */
    public $model;

    public function __construct(PermissionPermissions $model)
    {
        $this->model = $model;
        parent::__construct();
    }

    /**
     * 检查菜单权限
     * @param $navs
     * @param $model
     * @param $user
     * @param string $guardName
     * @return array
     * @throws \Exception
     * @author Blues
     */
    static public function checkNavPermission($navs,$model,$user,$guardName='admin')
    {
        /**
         * @var $user UserAdmin;
         */
        $newNavs = [];
        foreach ($navs as $key => $nav) {
            try {
                $flag = $user->hasPermissionTo($model . '-' . $nav['id'], $guardName);
            } catch (PermissionDoesNotExist $e) {
                //权限未创建
                self::createAllAdminNavPermission($guardName);
                $flag = false;
            }

            $flag || $newNavs[] = $nav;

        }
        return $newNavs;
    }


    /**
     * 检查是否有不存在的权限
     * @param string $guardName
     * @throws \Exception
     * @author Blues
     */
    static function createAllAdminNavPermission($guardName='admin'){
        $adminNavModel = Config('larfreePermission.models.adminNav');

        $permissionModel = Config('larfreePermission.models.permission');
        //获取没有生成权限的导航
        $permissions = $permissionModel::make()->where('guard_name',$guardName)->where('target_type',$adminNavModel)->get();
        $navsIds = $permissions->pluck('target_id');
        //获取没有权限的菜单
        $navs = app($adminNavModel)->whereNotIn('id',$navsIds->toArray())->select('id')->get();

        foreach ($navs as $nav){
            $insert = [
                'name' => $adminNavModel.'-' . $nav->id,
                'target_type' => $adminNavModel,
                'guard_name' => $guardName,
                'target_id' => $nav->id,
                'comment' => $nav->name
            ];
            static::make()->addOne($insert);
        }
    }


}
