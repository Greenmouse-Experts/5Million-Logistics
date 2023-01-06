<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pickup_services', function (Blueprint $table) {
            $table->id();
            $table->string('user_id')->nullable();
            $table->string('service_type')->default('Pickup')->index();
            $table->string('order_id')->nullable()->index();
            $table->string('tracking_number')->nullable()->index();
            $table->string('pickup_vehicle')->nullable();
            $table->string('pickup_address')->nullable();
            $table->string('dropoff_address')->nullable();
            $table->string('sender_address')->nullable();
            $table->string('sender_name')->nullable();
            $table->string('sender_phone_number')->nullable();
            $table->string('receiver_address')->nullable();
            $table->string('receiver_name')->nullable();
            $table->string('receiver_phone_number')->nullable();
            $table->string('price')->nullable();
            $table->string('comment')->nullable();
            $table->string('status')->default('New')->index();
            $table->string('progress')->nullable();
            $table->string('current_location')->nullable();
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
        Schema::dropIfExists('pickup_services');
    }
};
