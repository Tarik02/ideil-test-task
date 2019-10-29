<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlacePhotosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('place_photos', function (Blueprint $table) {
            $table->bigInteger('id');
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

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('place_photos');
    }
}
