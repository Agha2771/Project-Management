<?php

namespace ProjectManagement\Services;

use ProjectManagement\Repositories\User\UserRepositoryInterface;

class UserService
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository){
        $this->userRepository = $userRepository;
    }

    public function auth_data(){
        $data = auth()->user();
        return $data;
    }

}
