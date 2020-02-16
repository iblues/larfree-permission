<?php
/**
 * Larfree Api类
 * @author blues
 */
namespace LarfreePermission\Controllers\Permission;
use Illuminate\Http\Request;
use Larfree\Controllers\ApisController as Controller;
use LarfreePermission\Services\Permission\PermissionRolesService;
class RolesController extends Controller
{
    /**
     * @var PermissionRolesService
     */
    public $service;
    public function __construct(PermissionRolesService $service)
    {
        $this->service = $service;
        parent::__construct();
    }

    /**
     * 获取菜单列表,树状结构的
     * @author Blues
     */
    public function navTree(){

    }
}
