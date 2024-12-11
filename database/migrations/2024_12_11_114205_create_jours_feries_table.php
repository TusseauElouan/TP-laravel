<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jours_feries', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->date('date');
            $table->boolean('is_recurring')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jours_feries');
    }
};
