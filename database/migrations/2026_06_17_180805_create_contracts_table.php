<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->string('contract_number')->unique();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->foreignId('job_id')->nullable()->constrained()->onDelete('set null');
            $table->string('title');
            $table->enum('type', ['Wedding', 'Event', 'Commercial', 'Portrait', 'Other'])->default('Wedding');
            $table->date('event_date')->nullable();
            $table->string('event_location')->nullable();
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->decimal('advance_amount', 12, 2)->default(0);
            $table->date('contract_date');
            $table->longText('terms')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['Draft', 'Sent', 'Signed', 'Completed', 'Cancelled'])->default('Draft');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('contracts');
    }
};