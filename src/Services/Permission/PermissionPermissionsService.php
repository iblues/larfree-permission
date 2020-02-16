<?php
/**
 * 仓库类. 所有数据交互通过此模式
 * @author blues
 */
namespace LarfreePermission\Services\Permission;
use Larfree\Services\SimpleLarfreeService;
use LarfreePermission\Models\Permission\PermissionPermissions;
class PermissionPermissionsService extends SimpleLarfreeService
{
    /**
     * @var PermissionPermissions
     */
    public $model;
    public function __construct(PermissionPermissions $model )
    {
        $this->model = $model;
        parent::__construct();
    }
}
