<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameOrderToWeightInPlacePhotosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('place_photos', function (Blueprint $table) {
            $table->renameColumn('order', 'weight');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('place_photos', function (Blueprint $table) {
            $table->renameColumn('weight', 'order');
        });
    }
}
