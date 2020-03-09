<?php
/**
 * 没有任何逻辑的Model类
 * @author blues
 */

namespace LarfreePermission\Models\Permission;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Arr;
use Larfree\Models\Admin\AdminNav;
use Larfree\Models\Traits\Base;
use LarfreePermission\Services\Permission\PermissionPermissionsService;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionRoles extends Role
{
    use Base;

    protected $attributes = [
        'guard_name' => 'admin',
    ];

    /**
     * 在添加前,先简单和生成权限
     * 保存和添加的回调
     * @param Model $data
     * @throws \Exception
     */
    public function beforeSave(Model &$data)
    {

        if (isset($data->nav)) {
            //传过来的是nav id,转换为permission id.然后返回permission id进行保存
            $nav = $data->getAttribute('nav');
            if (!is_array($nav)) {
                unset($data->nav);
            } else {
                $nav = array_values($nav);
                //多维数组 这里的id是菜单id!!
                if ((count($nav) != count($nav, COUNT_RECURSIVE))) {
                    $nav = Arr::pluck($nav, 'id', 'id');
                    //单维数据
                }

                $permissionModel = Config('larfreePermission.models.permission');
                $adminNavModel = Config('larfreePermission.models.adminNav');

                $guard_name = $data->getAttribute('guard_name', 'admin');
                //检查有没有没有生成权限的菜单
                PermissionPermissionsService::make()->createAllAdminNavPermission($guard_name);

                //把菜单id 转换成菜单id.
                $permission = app($permissionModel)->select('id')->where('target_type', $adminNavModel)->whereIn('target_id', $nav)->get();
                $permissionId = $permission->pluck('id');

                $data->setAttribute('nav', $permissionId);
            }
        }


        // api模型.
        if(isset($data->api)){
            PermissionPermissionsService::make()->createAllAdminApiPermission($guard_name);
        }
    }





    public function afterSave(Model $data)
    {
        //清理缓存
        $this->forgetCachedPermissions();
    }

    /**
     * 菜单粒度的权限控制
     * @return BelongsToMany
     * @override
     * @author Blues
     */
    public function nav(): BelongsToMany
    {
        return $this->permissions()->where('type', 'nav');
    }


    /**
     * api粒度的权限控制
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     * @override
     * @author Blues
     */
    public function api(): BelongsToMany
    {
        return $this->permissions()->where('type', 'api');
    }


}
