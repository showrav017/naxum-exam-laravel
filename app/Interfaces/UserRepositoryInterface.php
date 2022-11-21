<?php

namespace App\Interfaces;

interface UserRepositoryInterface
{
    public function getAllUsers($search, $start_from, $page_limit);
    public function getUserById($user_id);
    public function deleteUser($user_id);
    public function createUser(array $userDetails);
    public function userNameExists($user_name):bool;
    public function updateUserPassword($user_id, array $newDetails);
    public function updateUser($user_id, array $newDetails);
}
