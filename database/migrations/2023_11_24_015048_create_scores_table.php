<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_team_id')->constrained('event_teams')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('date_number');
            $table->integer('match_number');
            $table->integer('kills');
            $table->integer('kills_image');
            $table->integer('position');
            $table->integer('position_image');
            $table->foreignId('multiplier_detail_id')->constrained('multiplier_details')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('scores');
    }
}
