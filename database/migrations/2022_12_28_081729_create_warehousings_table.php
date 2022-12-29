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
        Schema::create('warehousings', function (Blueprint $table) {
            $table->id();
            $table->string('user_id')->nullable();
            $table->string('tracking_number')->nullable()->index();
            $table->string('warehouse_location')->nullable();
            $table->string('package_name')->nullable();
            $table->string('package_quantity')->nullable();
            $table->string('package_dimension')->nullable();
            $table->string('package_weight')->nullable();
            $table->string('package_value')->nullable();
            $table->string('package_description')->nullable();
            $table->string('storage_start_date')->nullable();
            $table->string('storage_end_date')->nullable();
            $table->string('owner_full_name')->nullable();
            $table->string('owner_address')->nullable();
            $table->string('owner_phone_number')->nullable();
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
        Schema::dropIfExists('warehousings');
    }
};
