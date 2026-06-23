<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('invoices', 'share_token')) {
            Schema::table('invoices', function (Blueprint $table) {
                $table->string('share_token', 64)->nullable()->after('status');
            });
        }

        if (!Schema::hasColumn('quotations', 'share_token')) {
            Schema::table('quotations', function (Blueprint $table) {
                $table->string('share_token', 64)->nullable()->after('status');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('invoices', 'share_token')) {
            Schema::table('invoices', function (Blueprint $table) {
                $table->dropColumn('share_token');
            });
        }

        if (Schema::hasColumn('quotations', 'share_token')) {
            Schema::table('quotations', function (Blueprint $table) {
                $table->dropColumn('share_token');
            });
        }
    }
};