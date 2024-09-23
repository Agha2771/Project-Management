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
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
        $this->user = app(UserRepositoryInterface::class);
    }

    public function refresh(Request $request)
    {
        $token = $request->bearerToken();
        if (!$token) {
            return $this->failureResponse('Invalid token!', 400);
        }

        $newToken = JWTAuth::refresh($token);
        $responseData = [
            'message' => 'Success',
            'accessToken' => $newToken['access_token'],
            'tokenType' => $token['token_type'],
        ];
        return $this->successResponse($responseData,ResponseMessage::OK , Response::HTTP_OK);
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
}
