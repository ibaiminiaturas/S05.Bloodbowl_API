<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('team_players', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('player_type_id');
            $table->unsignedBigInteger('team_id');
            $table->string('name', 100);
            $table->unsignedTinyInteger('player_number');
            $table->unsignedTinyInteger('spp');
            $table->timestamps();
            $table->foreign('player_type_id')->references('id')->on('player_types')->onDelete('cascade');
            $table->foreign('team_id')->references('id')->on('teams')->onDelete('cascade');
            $table->unique(['team_id', 'player_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team_players');
    }
};
