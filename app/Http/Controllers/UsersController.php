<?php

namespace App\Http\Controllers;

use App\Interfaces\UserRepositoryInterface;
use App\Models\Users;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    private $loggedUserInfo;
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index(Request $request)
    {
        if(!$request->isSuperAdmin)
            return $this->notEnoughPermissionResponse();

        return $this->successResponse($this->userRepository->getAllUsers(0, 50));
    }

    public function create(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        if (empty($data)) self::badRequestResponse();

        if(!$request->isSuperAdmin)
            return $this->notEnoughPermissionResponse();

        $validator = Validator::make(json_decode($request->getContent(), true), [
            'user_name' => 'required|string',
            'user_password' => 'required|string',
        ]);

        if ($validator->fails())
            return $this->conflictResponse($validator->errors());

        if($this->userRepository->userNameExists($data['user_name']))
            return $this->conflictResponse([['user_exists'=>'User Name already exists on system']]);

        $this->userRepository->createUser($data);

        return $this->successResponse();
    }

    public function show(Request $request, $user_id)
    {
        $data = json_decode($request->getContent(), true);

        if (empty($data)) self::badRequestResponse();

        if(!$request->isSuperAdmin)
            return $this->notEnoughPermissionResponse();

        return $this->successResponse($this->userRepository->getUserById($user_id));
    }

    public function change_my_password(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        if (empty($data)) self::badRequestResponse();

        if(!$request->isSuperAdmin)
            return $this->notEnoughPermissionResponse();

        $this->userRepository->updateUserPassword($request->loggedUserDetails['user_id'], $data);

        return $this->successResponse();
    }

    public function change_password(Request $request, $user_id)
    {
        $data = json_decode($request->getContent(), true);

        if (empty($data)) self::badRequestResponse();

        if(!$request->isSuperAdmin)
            return $this->notEnoughPermissionResponse();

        $this->userRepository->updateUserPassword($user_id, $data);

        return $this->successResponse();
    }


    public function destroy(Request $request, $user_id)
    {
        $data = json_decode($request->getContent(), true);

        if (empty($data)) self::badRequestResponse();

        if(!$request->isSuperAdmin)
            return $this->notEnoughPermissionResponse();

        $this->userRepository->deleteUser($user_id);

        return $this->successResponse();
    }
}
