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
        Schema::create('express_shippings', function (Blueprint $table) {
            $table->id();
            $table->string('user_id')->nullable();
            $table->string('service_type')->default('ExpressShipping')->index();
            $table->string('order_id')->nullable()->index();
            $table->string('tracking_number')->nullable()->index();
            $table->string('freight_service')->nullable();
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
            $table->string('package_name')->nullable();
            $table->string('package_quantity')->nullable();
            $table->string('package_dimension')->nullable();
            $table->string('package_weight')->nullable();
            $table->string('package_value')->nullable();
            $table->string('package_description')->nullable();
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
        Schema::dropIfExists('express_shippings');
    }
};
