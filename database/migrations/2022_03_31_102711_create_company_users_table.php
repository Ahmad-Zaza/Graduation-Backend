<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_users', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('username');
            $table->string('phone_number');
            $table->string('email')->nullable();
            $table->string('password');
            $table->boolean('account_status')->default(false);
            $table->integer('user_type')->default(0);
            $table->string('photo_id')->nullable();
            $table->string('firebasetoken')->nullable();
            $table->integer('truck_id')->nullable();
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
        Schema::dropIfExists('company_users');
    }
}