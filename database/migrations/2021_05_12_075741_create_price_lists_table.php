<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePriceListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('price_lists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parking_id')
                ->constrained('parkings')
                ->onDelete('cascade')
                ->onUpdate('cascade');;
            $table->foreignId('vehicle_type_id')
                ->constrained('vehicle_types')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->decimal('price_per_hour', 5, 2, true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('price_lists');
    }
}
