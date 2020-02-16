<?php
/**
 * Larfree Api类
 * @author blues
 */
namespace LarfreePermission\Controllers\User;

use Illuminate\Http\Request;
use Larfree\Controllers\AdminApisController as Controller;
use LarfreePermission\Services\User\UserAdminService;
class AdminController extends Controller
{
    public function __construct(UserAdminService $service )
    {
        $this->service = $service;
        $this->service->setAdmin();
        parent::__construct();
    }

    public function show($id,Request $request){
        $id = $id==0?getLoginUserID():$id;// 0 equal LoginUser
        return parent::show($id,$request);
    }
}
