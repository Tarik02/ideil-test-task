<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropPlacePhotosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::drop('place_photos');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('place_photos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('place_id');
            $table->string('preview');
            $table->string('original');
            $table->bigInteger('order')->default(0);
            $table->boolean('visible')->default(true);
            $table->timestamps();

            $table->index('place_id');

            $table->foreign('place_id')
                ->references('id')->on('places')
                ->onDelete('cascade');
        });
    }
}
