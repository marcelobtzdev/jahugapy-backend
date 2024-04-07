<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->string('image');
            $table->foreignId('game_id')->nullable()->constrained('games')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('mode_id')->constrained('modes')->onUpdate('cascade')->onDelete('cascade');
            $table->integer('teams_quantity');
            $table->integer('dates_quantity');
            $table->integer('matchs_quantity');
            $table->boolean('registration_status');
            $table->boolean('status')->default(0);
            $table->foreignId('multiplier_id')->nullable()->constrained('multipliers')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamp('date')->nullable()->default(null);
            $table->integer('current_date')->nullable()->default(null);
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
        Schema::dropIfExists('events');
    }
}
