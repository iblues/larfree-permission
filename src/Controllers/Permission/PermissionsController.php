<?php
/**
 * Larfree Api类
 * @author blues
 */
namespace LarfreePermission\Controllers\Permission;
use Illuminate\Http\Request;
use Larfree\Controllers\ApisController as Controller;
use LarfreePermission\Services\Permission\PermissionPermissionsService;
class PermissionsController extends Controller
{
    /**
     * @varPermissionPermissionsService
     */
    public $service;
    public function __construct(PermissionPermissionsService $service)
    {
        $this->service = $service;
        parent::__construct();
    }
}
