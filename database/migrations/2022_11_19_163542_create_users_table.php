<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("SET SESSION sql_require_primary_key = 0;");
        Schema::create('users', function (Blueprint $table) {
            $table->string('user_id', 40)->primary();
            $table->string('user_name');
            $table->string('password');

            $table->string('profile_picture_location')->nullable(true);
            $table->string('first_name')->nullable(true);
            $table->string('last_name')->nullable(true);
            $table->string('mobile_number')->nullable(true);
            $table->string('email')->nullable(true);
            $table->string('facebook_url')->nullable(true);
            $table->string('linked_in_url')->nullable(true);
            $table->string('web_site')->nullable(true);

            $table->boolean('is_super_admin')->default(false);
            $table->dateTime('last_logged_at');
            $table->tinyInteger('removed')->default("0");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
