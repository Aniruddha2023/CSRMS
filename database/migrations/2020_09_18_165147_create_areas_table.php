<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAreasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('areas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('plot_code');
            $table->string('address');
            $table->integer('location_id');
            $table->integer('user_id');
            $table->string('contact');
            $table->integer('area_size');
            $table->string('description');
            $table->string('month');
            $table->integer('rent');
            $table->string('map_link')->nullable();
            $table->string('featured_image');
            $table->text('images');
            // $table->text('video');
            $table->string('status')->default(0);  //0 means Not-available
            $table->string('a_status')->default(0);  //0 means Pending For approval
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
        Schema::dropIfExists('houses');
    }
}
