<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->string('job_number')->unique();
            $table->string('title');
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->foreignId('quotation_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('type', ['Wedding', 'Portrait', 'Commercial', 'Event', 'Product', 'Other'])->default('Wedding');
            $table->date('event_date')->nullable();
            $table->string('event_location')->nullable();
            $table->text('description')->nullable();
            $table->enum('status', ['Inquiry', 'Confirmed', 'In Progress', 'Editing', 'Delivered', 'Completed', 'Cancelled'])->default('Inquiry');
            $table->enum('priority', ['Low', 'Medium', 'High'])->default('Medium');
            $table->decimal('budget', 12, 2)->nullable();
            $table->date('delivery_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('jobs');
    }
};