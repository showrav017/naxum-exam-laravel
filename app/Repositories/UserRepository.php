<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use App\Models\Contacts;
use App\Models\Users;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserRepositoryInterface
{
    public function getAllUsers($search = "", $start_from = 0, $page_limit = 50)
    {
        $userList = Users::where("removed", 0)->selectRaw("user_id, user_name, last_logged_at, updated_at")->take($page_limit)->skip($start_from);

        if(!empty($search)) $userList->where('user_name','LIKE','%'.$search.'%');

        $userList = $userList->get();

        $list = array();

        foreach ($userList as $user)
        {
            $list[] = array(
                $user->user_name,
                date("F d, Y h:i a", strtotime($user->last_logged_at)),
                $user->user_id
            );
        }

        $totalUserList = Users::where("removed", 0)->count();

        return [
            "userList"=>$list,
            "totalUserList"=>$totalUserList,
            "filteredUserList"=>count($list),
        ];
    }

    public function getUserById($user_id)
    {
        $user = Users::where("user_id", $user_id)->selectRaw("`user_id`, `user_name`, `last_logged_at`, `updated_at`, `profile_picture_location`, `first_name`, `last_name`, `mobile_number`, `email`, `facebook_url`, `linked_in_url`, `web_site`, `is_super_admin`")->first();

        $user->{"totalUserContacts"} = Contacts::where("removed", 0)->where("created_by", $user_id)->count();

        return $user;
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

    public function updateUserProfilePicture($user_id)
    {
        $existingUser = Users::find($user_id);
        $existingUser->updated_at = date("Y-m-d H:i:s");
        $existingUser->profile_picture_location = self::uploadFile("profile_picture");
        $existingUser->save();
    }

    public function userNameExists($user_name): bool
    {
        return (Users::where("user_name", $user_name)->count() > 0);
    }

    public function updateUser($user_id, array $newDetails)
    {
        $existingUser = Users::find($user_id);

        if(!empty($newDetails["first_name"]))
            $existingUser->first_name = $newDetails["first_name"];

        if(!empty($newDetails["last_name"]))
            $existingUser->last_name = $newDetails["last_name"];

        if(!empty($newDetails["mobile_number"]))
            $existingUser->mobile_number = $newDetails["mobile_number"];

        if(!empty($newDetails["email"]))
            $existingUser->email = $newDetails["email"];

        if(!empty($newDetails["facebook_url"]))
            $existingUser->facebook_url = $newDetails["facebook_url"];

        if(!empty($newDetails["linked_in_url"]))
            $existingUser->linked_in_url = $newDetails["linked_in_url"];

        if(!empty($newDetails["web_site"]))
            $existingUser->web_site = $newDetails["web_site"];

        $existingUser->updated_at = date("Y-m-d H:i:s");
        $existingUser->save();
    }


    private function uploadFile($field_name):string
    {
        $fileName = "";

        if(!empty($_FILES[$field_name]) && file_exists($_FILES[$field_name]["tmp_name"])) {
            $name = time() . '-' . rand(0, 1000) . '-' . preg_replace("/[^a-z0-9\_\-\.]/i", '', $_FILES[$field_name]["name"]);
            if(move_uploaded_file($_FILES[$field_name]["tmp_name"], public_path('profile_picture/').$name))
            {
                $fileName = $name;
            }
        }

        return $fileName;
    }
}
