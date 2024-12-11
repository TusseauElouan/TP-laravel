<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('color_preferences', function (Blueprint $table) {
            $table->string('text_color', 7)->after('background_color');
        });
    }

    public function down(): void {
        Schema::table('color_preferences', function (Blueprint $table) {
            $table->dropColumn('text_color');
        });
    }
};
