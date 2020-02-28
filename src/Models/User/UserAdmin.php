<?php
/**
 * 没有任何逻辑的Model类
 * @author blues
 */
namespace LarfreePermission\Models\User;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Larfree\Models\Api;

use Larfree\Models\ApiUser;
use Spatie\Permission\Traits\HasRoles;

class UserAdmin extends ApiUser
{
    use HasRoles;
    protected $guard_name = 'admin';

}
