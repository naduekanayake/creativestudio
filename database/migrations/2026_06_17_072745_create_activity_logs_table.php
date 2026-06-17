<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->string('action'); // created, updated, deleted
            $table->string('model_type'); // Client, Invoice, Job etc.
            $table->unsignedBigInteger('model_id')->nullable();
            $table->string('model_name')->nullable(); // e.g. "Naduni Ekanayake"
            $table->text('description');
            $table->string('icon')->default('activity');
            $table->string('color')->default('blue');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('activity_logs');
    }
};