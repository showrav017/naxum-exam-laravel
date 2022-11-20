<?php

namespace Database\Seeders;

use App\Models\Users;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $checkIfAnyUserExists = Users::all();
        if($checkIfAnyUserExists->count() == 0)
        {
            $super_admin = new Users();
            $super_admin->user_id = "6379b7e377c8eae446748e3421cc7";
            $super_admin->user_name = "admin";
            $super_admin->password = Hash::make("admin");
            $super_admin->last_logged_at = date("Y-m-d H:i:s");
            $super_admin->is_super_admin = 1;
            $super_admin->save();

            Users::factory()->count(5)->create();
        }
        else
        {
            Log::info("User Already Seeded");
        }
    }
}
