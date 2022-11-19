<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use App\Models\Users;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserRepositoryInterface
{
    public function getAllUsers($start_from = 0, $page_limit = 50)
    {
        return Users::where("removed", 0)->selectRaw("user_id, user_name, last_logged_at, updated_at")->take($page_limit)->skip($start_from)->get();
    }

    public function getUserById($user_id)
    {
        return Users::find($user_id);
    }

    public function deleteUser($user_id)
    {
        $existingUser = Users::find($user_id);
        $existingUser->removed = 1;
        $existingUser->save();
    }

    public function createUser(array $userDetails)
    {
        $newUser = new Users();
        $newUser->user_id = uniqid('').bin2hex(random_bytes(8));
        $newUser->user_name = $userDetails["user_name"];
        $newUser->password = Hash::make($userDetails["user_password"]);
        $newUser->last_logged_at = date("Y-m-d H:i:s");
        $newUser->save();
    }

    public function updateUserPassword($user_id, array $newDetails)
    {
        $existingUser = Users::find($user_id);
        $existingUser->updated_at = date("Y-m-d H:i:s");
        $existingUser->password = Hash::make($newDetails["new_password"]);
        $existingUser->save();
    }

    public function userNameExists($user_name): bool
    {
        return (Users::where("user_name", $user_name)->count() > 0);
    }
}
