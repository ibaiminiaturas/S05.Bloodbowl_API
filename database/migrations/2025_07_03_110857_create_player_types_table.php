<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('player_types', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->unsignedBigInteger('roster_id');
            $table->integer('max_per_team');
            $table->tinyInteger('movement');
            $table->tinyInteger('strength');
            $table->tinyInteger('agility');
            $table->tinyInteger('passing');
            $table->tinyInteger('armor');
            $table->integer('cost');

            $table->foreign('roster_id')->references('id')->on('rosters')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('player_types');
    }
};
