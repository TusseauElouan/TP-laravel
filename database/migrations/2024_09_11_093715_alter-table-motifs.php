<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('motifs', function (Blueprint $table) {
            $table->after('libelle', function (Blueprint $table) {
                $table->boolean('is_accessible_salarie')->default(true);
            });

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('motifs', function (Blueprint $table) {
            $table->dropColumn(['is_accessible_salarie']);
        });
    }
};
