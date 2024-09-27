<?php

namespace App\Http\Controllers;

use App\Notifications\ForgotPassword;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use ProjectManagement\Enums\ResponseMessage;
use ProjectManagement\Repositories\User\UserRepositoryInterface;
use ProjectManagement\Resources\UserResource;
use ProjectManagement\Traits\ApiResponseTrait;
use ProjectManagement\ValidationRequests\ForgotPasswordRequest;
use ProjectManagement\ValidationRequests\LoginRequests;
use ProjectManagement\ValidationRequests\PasswordResetRequest;
use ProjectManagement\ValidationRequests\UpdatePasswordRequest;
use ProjectManagement\ValidationRequests\createUserRequest;
use ProjectManagement\ValidationRequests\UpdateUserRequest;

use ProjectManagement\ValidationRequests\UserRegisterRequest;
use ProjectManagement\ValidationRequests\UserUpdateRequest;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Http\Request;
use ProjectManagement\Services\UserService;

class UserController extends Controller
{
    use ApiResponseTrait;
    protected $user;
    protected $userService;

    protected $userRepository;

    public function __construct(UserService $userService , UserRepositoryInterface $userRepository)
    {
        $this->userService = $userService;
        $this->userRepository = $userRepository;
        $this->user = app(UserRepositoryInterface::class);
    }

    public function refresh(Request $request)
    {
        $token = $request->bearerToken();
        if (!$token) {
            return $this->failureResponse('Invalid token!', 400);
        }

        try {
            $newToken = JWTAuth::refresh($token);

            // Assuming $newToken is a string, as JWTAuth::refresh typically returns a string token
            $responseData = [
                'message' => 'Success',
                'accessToken' => $newToken,
                'tokenType' => 'Bearer', // Typically, the token type is 'Bearer'
            ];

            return $this->successResponse($responseData, ResponseMessage::OK, Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->failureResponse('Failed to refresh token: ' . $e->getMessage(), 500);
        }
    }
    public function getUsers (Request $request)
    {
        $perPage = $request->input('page_size', 10);
        $pageNum = $request->input('page_num', 1);
        $search = $request->input('search', '');
        $tasks = $this->userRepository->paginate($perPage, ['*'], 'page', $pageNum, $search);
        return $this->successResponse([
            'data' => UserResource::collection($tasks),
            'total_records' => $tasks->total(),
            'current_page' => $tasks->currentPage(),
            'total_pages' => $tasks->lastPage(),
            'page_num' => $pageNum,
            'per_page' => $perPage,
        ], ResponseMessage::OK, Response::HTTP_OK);
    }
    public function register(UserRegisterRequest $request){
        $user = $this->user->create($request->prepareRequest());
        if($user){
            $data = [
                'email' => $user->email,
                'password' => $request['password'],
            ];

            if (!$token = $this->guard('api')->attempt($data)) {
                return $this->failureResponse( 'Unauthorized', 401);
            }
                $token =  $this->respondWithToken($token);
                $responseData = [
                    'message' => 'Successfully registered, Image Hosting sent you a verification code on this email',
                    'accessToken' => $token['access_token'],
                    'tokenType' => $token['token_type'],
                ];

                return $this->successResponse($responseData,ResponseMessage::CREATED , Response::HTTP_OK);
        }
    }

    public function login(LoginRequests $requests){
        $data = $requests->prepareRequest();
        if (!$token = $this->guard('api')->attempt($data)) {
            return $this->failureResponse( 'Unauthorized', 401);
        }
        $token =  $this->respondWithToken($token);
        $responseData = [
            'message' => 'Successfully login',
            'accessToken' => $token['access_token'],
            'tokenType' => $token['token_type'],
        ];
        return $this->successResponse($responseData,ResponseMessage::OK , Response::HTTP_OK);
    }


    protected function respondWithToken($token)
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->guard('api')->factory()->getTTL() * 120
        ];
    }

    public function guard($guard=null)
    {
        return Auth::guard($guard);
    }

    public function update(UserUpdateRequest $request){
        $userId = auth()->user()->id;
        $user = $this->user->update($request->prepareRequest(),$userId);
        if($user){
            return $this->successResponse(new UserResource($user),ResponseMessage::UPDATED , Response::HTTP_OK);
        }
        return $this->failureResponse(ResponseMessage::MESSAGE_500);
    }

    public function authentication(){
        $user = $this->userService->auth_data();
        return $this->successResponse(new UserResource($user),ResponseMessage::OK , Response::HTTP_OK);
    }

    public function create(CreateUserRequest $request){
        $data = $request->prepareRequest();
        $user = $this->userRepository->create($data);
        return $this->successResponse(new UserResource($user),ResponseMessage::OK , Response::HTTP_OK);
    }
    public function update_user(UpdateUserRequest $request , $id){
        $data = $request->prepareRequest();
        $user = $this->userRepository->update($data , $id);
        return $this->successResponse(new UserResource($user),ResponseMessage::OK , Response::HTTP_OK);
    }

    public function destroy($id){
        $user = $this->userRepository->find($id);
        if ($user){
            $user = $this->userRepository->delete( $id);
              return $this->successResponse('',ResponseMessage::OK , Response::HTTP_OK);
        }else{
            return $this->successResponse( '', ResponseMessage::ERROR , Response::HTTP_OK);

        }
    }
}
