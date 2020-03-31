<?php
/**
 * 仓库类. 所有数据交互通过此模式
 * @author blues
 */

namespace LarfreePermission\Services\User;

use App\Models\Common\CommonUser;
use Illuminate\Support\Arr;
use Larfree\Services\SimpleLarfreeService;
use LarfreePermission\Models\User\UserAdmin;
use LarfreePermission\Repositories\User\UserAdminRepository;
use function GuzzleHttp\Psr7\str;

class UserAdminService extends SimpleLarfreeService
{
    /**
     * @var UserAdmin
     */
    public $model;

    public function __construct()
    {
        $model = config('larfreePermission.models.userAdmin', UserAdmin::class);
        $this->model = app($model);
        parent::__construct();
    }

    public function addAdminAndUser($data)
    {
        $user = CommonUser::updateOrCreate(
            ['phone' => $data['user.phone']],
            [
                'phone' => $data['user.phone'],
                'email' => $data['user.email'] ?? null,
                'password' => $data['user.password'] ?? null,
            ]
        );
        $data['user_id'] = $user->id;
        return $this->addOne($data);
    }


}
