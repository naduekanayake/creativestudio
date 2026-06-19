<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('reminders', function (Blueprint $table) {
            $table->string('source_type')->nullable()->after('id');
            $table->unsignedBigInteger('source_id')->nullable()->after('source_type');
        });
    }
    public function down(): void {
        Schema::table('reminders', function (Blueprint $table) {
            $table->dropColumn(['source_type', 'source_id']);
        });
    }
};