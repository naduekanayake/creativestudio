<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['super_admin', 'admin', 'staff'])->default('staff')->after('email');
            $table->string('phone')->nullable()->after('role');
            $table->string('position')->nullable()->after('phone');
            $table->boolean('is_active')->default(true)->after('position');
        });
    }

    public function down(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'phone', 'position', 'is_active']);
        });
    }
};