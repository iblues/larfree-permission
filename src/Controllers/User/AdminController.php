<?php
/**
 * Larfree Api类
 * @author blues
 */

namespace LarfreePermission\Controllers\User;

use App\Models\Common\CommonUser;
use Iblues\AnnotationTestUnit\Annotation as ATU;
use Illuminate\Http\Request;
use Larfree\Controllers\AdminApisController as Controller;
use LarfreePermission\Services\User\UserAdminService;
use LarfreePermission\Models\User\UserAdmin;
use OpenApi\Annotations as OA;


class AdminController extends Controller
{

    public $in = [
        'store' => [
            '*',
            'user_id' => [
                'rule' => ['unique:user_admin,user_id' => '该用户已添加,请勿重复添加']
            ]
        ],
        'update' => [
            '*',
            'user_id' => [
                'rule' => ['unique:user_admin,user_id,id,admin' => '该用户已添加,请勿重复添加']
            ]
        ]
    ];

    public function __construct(UserAdminService $service)
    {
        $this->service = $service;
        $this->service->setAdmin();
        parent::__construct();
    }

    /**
     * @param $id
     * @param Request $request
     * @return \Larfree\Services\model
     * @throws \Exception
     * @author Blues
     * @ATU\Api(
     * )
     */
    public function show($id, Request $request)
    {
        $id = $id == 0 ? getLoginUserID() : $id;// 0 equal LoginUser
        return parent::show($id, $request);
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws \Exception
     * @author Blues
     * @ATU\Api(
     *     @ATU\Before(@ATU\Tag("addUserAdmin")),
     *     @ATU\Request({"page":1,"@sort":"user.name.desc","user.phone|name":@ATU\GetParam("addUserAdmin.response.data.name")}),
     *     @ATU\Response({"data":{{"id":true}}}),
     * )
     */
    public function index(Request $request)
    {
        return parent::index($request); // TODO: Change the autogenerated stub
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws \Exception
     * @author Blues
     * @ATU\Api(
     *     title="user_id:1的应该不能重复添加",
     *     @ATU\Before("create",{ UserAdmin::class,{"user_id":1},{"user_id":1} }),
     *     @ATU\Request({"user_id":1,"name":"测试123","roles":{1},"user.email":"i@Iblues.name","user.password":"123"}),
     *     @ATU\Response(422, {"msg":"该用户已添加,请勿重复添加"} ),
     * )
     * @ATU\Api(
     *     title="先删除user_id:1的 避免因为重复添加不进去",
     *     @ATU\Tag("addUserAdmin"),
     *     @ATU\Before("delete",{ UserAdmin::class,{"user_id":1}}),
     *     @ATU\Before("delete",{ CommonUser::class,{"phone":13888888881}}),
     *     @ATU\Request({"name":"测试2333","user.email":"i@iblues.com2","user.phone":"13888888881","user.password":"1234","roles":{1}}),
     *     @ATU\Response({
     *      "data":{"roles":{{"id":1}},"user_id":@ATU\GetRequest("user_id"),"user":true,"name":@ATU\GetRequest("name")}
     *     }),
     *     @ATU\Assert("assertDatabaseHas",{"user_admin",{"user_id":@ATU\GetResponse("data.user_id")}}),
     *     @ATU\Assert("assertDatabaseHas",{"common_user",{"email":@ATU\GetRequest("user.email")}}),
     *     @ATU\Assert("assertDatabaseHas",{"permission_model_has_roles",{"role_id":1,"model_type":UserAdmin::class,"model_id":@ATU\GetResponse("data.id")}}),
     *     @ATU\Assert("assertLog",{"新增管理员信息"})
     *
     * )
     */
    public function store(Request $request)
    {
        return $this->service->addAdminAndUser($request->all());
    }

    /**
     * @param Request $request
     * @param $id
     * @return mixed
     * @throws \Exception
     * @author Blues
     * @ATU\Api(
     *     title="编辑.变user_id:1改成2. 为了避免脏数据. 重置userAdmin和CommonUser",
     *     path="oldest",
     *     @ATU\Before("create",{ UserAdmin::class,{"user_id":1},{"user_id":1} }),
     *     @ATU\Before("create",{ CommonUser::class,{"id":2},{"id":2} }),
     *     @ATU\Request({"user_id":2,"name":"测试2","roles":{1}}),
     *     @ATU\Response({
     *      "data":{"roles":{{"id":1}},"user_id":@ATU\GetRequest("user_id"),"user":true,"name":@ATU\GetRequest("name")}
     *     }),
     *     @ATU\Assert("assertDatabaseHas",{"user_admin",{"user_id":@ATU\GetRequest("user_id")}}),
     *     @ATU\Assert("assertDatabaseHas",{"user_admin",{"name":@ATU\GetRequest("name")}}),
     *     @ATU\Assert("assertDatabaseHas",{"permission_model_has_roles",{"role_id":1,"model_type":UserAdmin::class,"model_id":@ATU\GetResponse("data.id")}})
     * )
     *
     * @ATU\Api(
     *     path=@ATU\GetParam("addUserAdmin.response.data.id"),
     *     title="同id的唯一索引测试",
     *     @ATU\Before(@ATU\Tag("addUserAdmin")),
     *     @ATU\Request({"user_id":@ATU\GetParam("addUserAdmin.response.data.user_id"),"namer":"测试","roles":{1}})
     * )
     *
     * @ATU\Api(
     *     path="latest",
     *     title="user_id:1的应该不能重复添加",
     *     @ATU\Before("create",{ UserAdmin::class,{"user_id":1},{"user_id":1} }),
     *     @ATU\Request({"user_id":1,"namer":"测试","roles":{1}}),
     *     @ATU\Response(422, {"msg":"该用户已添加,请勿重复添加","code":true} ),
     * )
     */
    public function update(Request $request, $id)
    {
        return parent::update($request, $id);
    }
}
