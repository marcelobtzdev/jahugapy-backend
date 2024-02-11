<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMultiplierDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('multiplier_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('multiplier_id')->constrained('multipliers')->onUpdate('cascade')->onDelete('cascade');
            $table->float('value');
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
        Schema::dropIfExists('multiplier_details');
    }
}
