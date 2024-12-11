<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('color_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('motif_id')->constrained()->onDelete('cascade');
            $table->string('background_color', 7); // Pour stocker les codes HEX (#FFFFFF)
            $table->string('border_color', 7);
            $table->timestamps();

            $table->unique(['user_id', 'motif_id']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('color_preferences');
    }
};
