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
        Schema::create('procurements', function (Blueprint $table) {
            $table->id();
            $table->string('user_id')->nullable();
            $table->string('tracking_number')->nullable()->index();
            $table->string('item_name')->nullable();
            $table->string('item_type')->nullable();
            $table->string('item_store_name')->nullable();
            $table->string('item_description')->nullable();
            $table->string('item_tracking_id')->nullable();
            $table->string('item_value')->nullable();
            $table->string('owner_full_name')->nullable();
            $table->string('owner_address')->nullable();
            $table->string('owner_email')->nullable();
            $table->string('owner_phone_number')->nullable();
            $table->string('date_of_shipment')->nullable();
            $table->string('shipping_from_street_address')->nullable();
            $table->string('shipping_from_city')->nullable();
            $table->string('shipping_from_state_province_region')->nullable();
            $table->string('shipping_from_zip_portal_code')->nullable();
            $table->string('shipping_from_country')->nullable();
            $table->string('shipping_to_street_address')->nullable();
            $table->string('shipping_to_city')->nullable();
            $table->string('shipping_to_state_province_region')->nullable();
            $table->string('shipping_to_zip_portal_code')->nullable();
            $table->string('shipping_to_country')->nullable();
            $table->string('price')->nullable();
            $table->string('comment')->nullable();
            $table->string('status')->default('Pending')->index();
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
        Schema::dropIfExists('procurements');
    }
};
