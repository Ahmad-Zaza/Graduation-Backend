<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;

class CreateSubscribeRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscribe_requests', function (Blueprint $table) {
            $table->id();
            $table->integer('retail_dealer_id');
            $table->integer('company_id');
            $table->integer('status')->default(Config::get('constants.retailDealer.subscribeRequest.pending'));
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
        Schema::dropIfExists('subscribe_requests');
    }
}