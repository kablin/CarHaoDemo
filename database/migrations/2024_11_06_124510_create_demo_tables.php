<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {

       Schema::create('engine_types', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->timestamps();
        });


        Schema::create('drive_types', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->timestamps();
        });


        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->timestamps();
        });

        Schema::create('car_models', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('brand_id')->unsigned();
            $table->string('title');
            $table->timestamps();

            $table->foreign('brand_id')->references('id')->on('brands')->cascadeOnUpdate()->cascadeOnDelete();
        });


        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('engine_type_id')->unsigned();
            $table->bigInteger('drive_type_id')->unsigned();
            $table->bigInteger('car_model_id')->unsigned();
            $table->date('manufactured_year');
            $table->bigInteger('price');

            $table->timestamps();

            $table->foreign('engine_type_id')->references('id')->on('engine_types')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('drive_type_id')->references('id')->on('drive_types')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreign('car_model_id')->references('id')->on('car_models')->cascadeOnUpdate()->cascadeOnDelete();
        });

        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('car_id')->unsigned();
            $table->string('path');
            $table->timestamps();

            $table->foreign('car_id')->references('id')->on('cars')->cascadeOnUpdate()->cascadeOnDelete();
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('images');
        Schema::dropIfExists('cars');
        Schema::dropIfExists('engine_types');
        Schema::dropIfExists('drive_types');
        Schema::dropIfExists('car_models');
        Schema::dropIfExists('brands');  

    }
};
