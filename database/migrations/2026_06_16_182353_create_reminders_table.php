<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('reminders', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->foreignId('client_id')->nullable()->constrained()->onDelete('cascade');
            $table->enum('type', ['Payment', 'Delivery', 'Shoot', 'Follow Up', 'Other'])->default('Other');
            $table->date('remind_date');
            $table->time('remind_time')->nullable();
            $table->enum('status', ['Pending', 'Done', 'Snoozed'])->default('Pending');
            $table->enum('priority', ['Low', 'Medium', 'High'])->default('Medium');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('reminders');
    }
};