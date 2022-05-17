<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRetailDealersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('retail_dealers', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('username');
            $table->string('phone_number');
            $table->string('email')->nullable();
            $table->string('password');
            $table->boolean('account_status')->default(true);
            $table->integer('user_type')->default(0);
            $table->string('photo_id')->nullable();
            $table->double('longitude');
            $table->double('latitude');
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
        Schema::dropIfExists('retail_dealers');
    }
}