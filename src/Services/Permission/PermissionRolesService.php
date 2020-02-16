<?php
/**
 * 仓库类. 所有数据交互通过此模式
 * @author blues
 */
namespace LarfreePermission\Services\Permission;
use Larfree\Services\SimpleLarfreeService;
use LarfreePermission\Models\Permission\PermissionRoles;
use LarfreePermission\Repositories\Permission\PermissionRolesRepository;
class PermissionRolesService extends SimpleLarfreeService
{
    /**
     * @var PermissionRoles
     */
    public $model;
    public function __construct(PermissionRoles $model )
    {
        $this->model = $model;
        parent::__construct();
    }

//Role::create(['guard_name' => 'admin', 'name' => 'superadmin']);
}
