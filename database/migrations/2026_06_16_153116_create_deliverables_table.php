<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('deliverables', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->foreignId('job_id')->nullable()->constrained('jobs')->onDelete('set null');
            $table->enum('type', ['Photos', 'Videos', 'Album', 'Raw Files', 'Edited Files', 'Prints', 'Other'])->default('Photos');
            $table->integer('quantity')->nullable();
            $table->string('delivery_method')->nullable();
            $table->date('due_date')->nullable();
            $table->date('delivered_date')->nullable();
            $table->string('drive_link')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['Pending', 'In Progress', 'Ready', 'Delivered', 'Approved'])->default('Pending');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('deliverables');
    }
};