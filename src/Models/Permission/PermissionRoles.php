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
     * @param $data
     * @throws \Larfree\Exceptions\ApiException
     */
    public function beforeSave(Model &$data)
    {

        if (isset($data->nav)) {
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
                PermissionPermissionsService::createAllAdminNavPermission($guard_name);

                //把菜单id 转换成菜单id.
                $permission = app($permissionModel)->select('id')->where('target_type', $adminNavModel)->whereIn('target_id', $nav)->get();
                $permissionId = $permission->pluck('id');

                $data->setAttribute('nav', $permissionId);
            }
        }
    }


    public function afterSave(Model $data)
    {
        //清理缓存
        $this->forgetCachedPermissions();
    }

    /**
     * 菜单粒度的权限控制
     * @author Blues
     * @return BelongsToMany
     * @override
     */
    public function nav(): BelongsToMany
    {
        return $this->permissions()->where('target_type', Config('larfreePermission.models.adminNav'));
    }


    /**
     * api粒度的权限控制
     * @author Blues
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     * @override
     */
    public function api(): BelongsToMany
    {
        return $this->permissions()->where('target_type', 'api');
    }


}
