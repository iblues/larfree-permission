<?php
/**
 * 仓库类. 所有数据交互通过此模式
 * @author blues
 */

namespace LarfreePermission\Services\Permission;

use EasyWeChat\Kernel\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
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
     * @param  string  $guardName
     * @return array
     * @throws \Exception
     * @author Blues
     */
    public function checkNavPermission($navs, $user, $guardName = 'admin')
    {
        if(!method_exists($navs,'first'))
            return [];
        if(!$navs->first())
            return [];
        $model = get_class($navs->first());
        $superUserId = config('larfreePermission.super_admin', 0);
        $superUserId = explode(',', $superUserId);
        //是超级管理员
        if (in_array($user->id, $superUserId)) {
            return $navs;
        }

        $NavCollect = $navs;
        /**
         * @var $user UserAdmin;
         */
        $newNavs = [];
        foreach ($navs as $key => $nav) {
            try {
                $flag = $user->hasPermissionTo($model.'-'.$nav['id'], $guardName);
                //如果他没有权限 , 先检查下他的下级有没有权限.
                if (!$flag) {
                    $children = $NavCollect->where('parent_id', $nav['id']);

                    $flag = $this->checkNavPermission($children, $user, $guardName);
                    $flag = count($flag)>0?true:false;
                }
            } catch (PermissionDoesNotExist $e) {
                //权限未创建
                $this->createAllAdminNavPermission($guardName);
                $flag = false;
            }
            if ($flag) {
                $newNavs[] = $nav;
            }
        }
        return collect($newNavs);
    }

    /**
     * 检查是否有不存在的权限
     * @param  string  $guardName
     * @throws \Exception
     * @author Blues
     */
    public function createAllAdminNavPermission($guardName = 'admin')
    {
        $adminNavModel = Config('larfreePermission.models.adminNav');
        //获取没有生成权限的导航
        $permissions = $this->model->where('guard_name', $guardName)->where('target_type', $adminNavModel)->get();
        $navsIds = $permissions->pluck('target_id');
        //获取没有权限的菜单
        $navs = app($adminNavModel)->whereNotIn('id', $navsIds->toArray())->select('id')->get();

        foreach ($navs as $nav) {
            $insert = [
                'name' => $adminNavModel.'-'.$nav->id,
                'target_type' => $adminNavModel,
                'guard_name' => $guardName,
                'target_id' => $nav->id,
                'type' => 'nav',
                'comment' => $nav->name
            ];
            $this->addOne($insert);
        }
        //清理缓存
        $this->model->forgetCachedPermissions();
    }


    /**
     * 根据路由创建所有的路由
     * @author Blues
     */
    public function createAllAdminApiPermission($guardName = 'admin')
    {
        $routers = Route::getRoutes();
        $adminPrefix = config('larfreePermission.filter_admin_url', '');
        $modulefilter = config('larfreePermission.filter_admin_module_url', '');
        $lang = [
            'common.user' => '用户管理',
            'admin.nav' => '后台菜单',
            'permission.permission' => '权限明细',
            'permission.roles' => '权限角色',
            'user.admin' => '管理员',
            'upload.images' => '图片上传',
            'upload.files' => '文件上传',
            'test.test' => '测试模块',
        ];

        $data = [];
        $black = [
            "GET:///swagger/json",
            "POST:///user/session", //登录
            "DELETE:///user/session", //登录
            "GET:///permission/roles/nav/tree", //菜单
            "GET:///user/admin/{{}}", //当前登录admin
            "GET:///system/component/{{}}",
        ];
        $datas = [];
        foreach ($routers as $route) {
            $url = $route->uri;
            $method = strtoupper($route->methods[0]);
            $as = @$route->action['controller'];
            if (substr($url, 0, strlen($adminPrefix)) == $adminPrefix) {
                if ($url = substr($url, strlen($adminPrefix) + 1)) {
                    $api = $method.':///'.$url;
                    $datas[] = ['path' => $as, 'method' => $method, 'url' => $url, 'api' => $api];
                }
            }
        }

        foreach ($datas as $r) {
            $api = $r['api'];
            $api = $this->apiToPermissionApi($api);

            $state = 1;
            //黑名单内的
            if (in_array($api, $black)) {
                $state = 0;
            }

            $match = [];
            preg_match_all($modulefilter, $r['url'], $match);
            $action = '';
            switch ($r['method']) {
                case 'GET':
                    if (stripos($api, '{{}}')) {
                        $action = '详情';
                    } else {
                        $action = '列表';
                    }
                    break;
                case 'POST':
                    $action = '添加';
                    break;
                case 'PUT':
                case 'PATCH':
                    $action = '修改';
                    break;
                case 'DELETE':
                    $action = '删除';
                    break;
            }
            if ($match) {
                $comment = [];
                $model = ($match[1][0] ?? 'noGroup').".".($match[2][0] ?? '');
                $comment[] = $lang[$model] ?? $model;
                $comment[] = $action;
                $comment = implode(' : ', $comment);
            }

            $data = [
                'name' => $api,
                'guard_name' => $guardName,
                'type' => 'api',
                'comment' => $comment,
                'state' => $state,
            ];
            //没有创建过的再创建
            if (!$this->model->where('name', $api)->where('guard_name', $guardName)->first()) {
                $this->model->query()->create($data);
            }
        }
    }

    /**
     * 处理xx.com/{id} → xx.com/{{}}
     * @param $api
     * @return string|string[]|null
     * @author Blues
     *
     */
    protected function apiToPermissionApi($api)
    {
        return preg_replace('/\{.*\}/i', '{{}}', $api);
    }


    /**
     * 获取树状的Api列表, 前端选择用
     * @param  string  $guardName
     * @return array
     * @author Blues
     */
    public function getApiTree($guardName = 'admin')
    {
        $apis = $this->model->where('type', 'api')->orderBy('comment', 'desc')->where('guard_name',
            $guardName)->where('state', 1)->get();
        $return = [];
        foreach ($apis as $api) {
            $module = explode(':', $api['comment']);
            if (!isset($return[$module[0]])) {
                $return[$module[0]] = $api->toArray();
                $return[$module[0]]['comment'] = $module[0] ? $module[0] : '未归类';
                $return[$module[0]]['children'] = [];
            }
            $return[$module[0]]['children'][] = $api;
        }
        return array_values($return);
    }

    public function checkApiPermission($api, $user, $guardName = 'admin')
    {
        if (!is_string($api)) {
            return true;
        }
        $api = $this->apiToPermissionApi($api);
        try {
            $flag = $user->hasPermissionTo($api, $guardName);
        } catch (PermissionDoesNotExist $e) {
            //权限未创建. 代表空权限的.
            $this->createAllAdminApiPermission($guardName);
            $flag = false;
        }
        return $flag;
    }

    /**
     * 检查有没有api权限
     * @param $schemas
     * @param $user
     * @param  string  $guardName
     * @return array
     * @author Blues
     */
    public function checkApiSchemas($schemas, $user, $guardName = 'admin')
    {
        $superUserId = config('larfreePermission.super_admin', 0);
        $superUserId = explode(',', $superUserId);
        //是超级管理员
        if (in_array($user->id, $superUserId)) {
            return $schemas;
        }

        //递归检查数字, 如果有api字段就要检查.
        if (is_array($schemas)) {
            foreach ($schemas as $key => $schema) {
                $schemas[$key] = $this->checkApiSchemas($schema, $user, $guardName);
                if (isset($schema['api'])) {
                    //检查api在不在
                    $flag = $this->checkApiPermission($schema['api'], $user, $guardName);
                    if (!$flag) {
                        unset($schemas[$key]);
                    }
                }
            }
            return $schemas;
        } else {
            return $schemas;
        }
    }

}
