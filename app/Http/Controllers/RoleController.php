<?php

namespace  App\Http\Controllers;

use ProjectManagement\Traits\ApiResponseTrait;
use ProjectManagement\Enums\ResponseMessage;
use Symfony\Component\HttpFoundation\Response;
use ProjectManagement\Repositories\Role\RoleRepositoryInterface;
use ProjectManagement\Resources\RoleResource;
use ProjectManagement\Resources\PermissionsResource;
use ProjectManagement\ValidationRequests\CreateRoleRequest;
use ProjectManagement\Repositories\User\UserRepositoryInterface;
use ProjectManagement\ValidationRequests\UpdateRoleRequest;
use Illuminate\Http\Request;


class RoleController extends Controller
{
    protected $roleRepository;
    protected $userRepository;
    use ApiResponseTrait;

    public function __construct(RoleRepositoryInterface $roleRepository , UserRepositoryInterface $userRepository)
    {
        $this->roleRepository = $roleRepository;
        $this->userRepository = $userRepository;

        // $this->middleware('permission:view role', ['only' => ['index']]);
        // $this->middleware('permission:create role', ['only' => ['create', 'store', 'addPermissionToRole', 'givePermissionToRole']]);
        // $this->middleware('permission:update role', ['only' => ['update', 'edit']]);
        // $this->middleware('permission:delete role', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $perPage = $request->input('page_size', 10);
        $pageNum = $request->input('page_num', 1);
        $search = $request->input('search', '');
        $roles = $this->roleRepository->paginate($perPage, ['*'], 'page', $pageNum, $search);
        return $this->successResponse([
            'data' => RoleResource::collection($roles),
            'total_records' => $roles->total(),
            'current_page' => $roles->currentPage(),
            'total_pages' => $roles->lastPage(),
            'page_num' => $pageNum,
            'per_page' => $perPage,
        ], ResponseMessage::OK, Response::HTTP_OK);
    }
    public function create(CreateRoleRequest $request)
    {
        $data = $request->prepareRequest();
        $role = $this->roleRepository->create($data);
        $this->roleRepository->syncPermissions($role->id, $data['permissions']);
        return $this->successResponse(new RoleResource($role), ResponseMessage::OK , Response::HTTP_OK);
    }

    public function update(UpdateRoleRequest $request, $id)
    {
        $data = $request->prepareRequest();
        $role = $this->roleRepository->update($id , $data);
        return $this->successResponse(new RoleResource($role), ResponseMessage::OK , Response::HTTP_OK);
    }

    public function permissions(){
        $permissions = $this->roleRepository->getAllPermissions();
        return $this->successResponse($permissions, ResponseMessage::OK , Response::HTTP_OK);
    }
    public function destroy($id , $new_role_id)
    {
        $new_role = $this->roleRepository->find($new_role_id);
        $role = $this->roleRepository->find($id);
        if ($role and $new_role) {
            $usersWithRole = $this->userRepository->getUserWithSameRole($role->name);
            foreach ($usersWithRole as $user) {
                $user->syncRoles($new_role);
            }
            $this->roleRepository->delete($id);
        }else{
            return $this->failureResponse('Role not found!', Response::HTTP_NOT_FOUND);
        }
        return $this->successResponse('', ResponseMessage::OK , Response::HTTP_OK);

    }

    public function getAllPermissions()
    {
        $permissions = $this->roleRepository->getAllPermissions();
        return $this->successResponse(PermissionsResource::collection($permissions), ResponseMessage::OK , Response::HTTP_OK);
    }
}
