<?php
/**
 * 仓库类. 所有数据交互通过此模式
 * @author blues
 */
namespace LarfreePermission\Services\User;
use Larfree\Services\SimpleLarfreeService;
use LarfreePermission\Models\User\UserAdmin;
use LarfreePermission\Repositories\User\UserAdminRepository;
class UserAdminService extends SimpleLarfreeService
{
    /**
     * @var UserAdmin
     */
    public $model;
    public function __construct()
    {
        $model = config('larfree_permission_model',UserAdmin::class);
        $this->model = app($model);
        parent::__construct();
    }

}
