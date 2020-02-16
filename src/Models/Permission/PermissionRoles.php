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
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionRoles extends Role
{
    use Base;

    protected $attributes = [
        'guard_name' => 'admin',
    ];

    /**
     * 保存和添加的回调
     * @param $data
     */
    public function beforeSave(Model $data)
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


                $permissionId = [];

                //检查权限是否存在
                foreach ($nav as $id) {
                    //如果没有对应的权限, 先创建
                    if (!$permission = app($permissionModel)->where('target_type', 'nav')->where('target_id', $id)->first()) {
                        $nav = app($adminNavModel)->find($id);
                        $guard_name = $data->getAttribute('guard_name', 'admin');
                        //权限不存在,需要新建
                        $insert = [
                            'name' => 'nav-' . $id,
                            'target_type' => 'nav',
                            'guard_name' => $guard_name,
                            'target_id' => $id,
                            'comment' => $nav->name
                        ];
                        $permission = app($permissionModel)->create($insert);
//                        apiError("不存在权限:{$id},请先新建权限");
                    }
                    $permissionId[] = $permission['id'];
                }
                if(!$data->nav()->sync($permissionId)){
                    apiError('权限修改失败');
                }
//                $data->setAttribute('nav', $permissionId);
            }
        }
    }


    /**
     * 菜单粒度的权限控制
     * @author Blues
     * @return BelongsToMany
     */
    public function nav(): BelongsToMany
    {
        return $this->permissions()->where('target_type', 'nav');
    }


    /**
     * api粒度的权限控制
     * @author Blues
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function api(): BelongsToMany
    {
        return $this->permissions()->where('target_type', 'api');
    }


}
