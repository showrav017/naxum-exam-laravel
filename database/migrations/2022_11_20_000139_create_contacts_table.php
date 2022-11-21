<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("SET SESSION sql_require_primary_key = 0;");
        Schema::create('contacts', function (Blueprint $table) {
            $table->string('contact_id', 40)->primary();

            $table->string('name')->nullable(false);
            $table->string('phone_number')->nullable(false);
            $table->string('mobile_number')->nullable(true);
            $table->string('work_number')->nullable(true);
            $table->string('email')->nullable(true);
            $table->string('created_by', 40);


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
        Schema::dropIfExists('contacts');
    }
}
