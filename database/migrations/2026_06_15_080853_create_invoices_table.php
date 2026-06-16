<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->enum('type', ['Advance', 'Tax', 'Final'])->default('Tax');
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->foreignId('quotation_id')->nullable()->constrained()->onDelete('set null');
            $table->string('project_event')->nullable();
            $table->date('issue_date');
            $table->date('due_date');
            $table->decimal('sub_total', 12, 2)->default(0);
            $table->decimal('discount_percent', 5, 2)->default(0);
            $table->decimal('vat_percent', 5, 2)->default(0);
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->decimal('paid_amount', 12, 2)->default(0);
            $table->enum('payment_status', ['Unpaid', 'Partial', 'Paid'])->default('Unpaid');
            $table->text('terms')->nullable();
            $table->string('payment_terms')->nullable();
            $table->enum('status', ['Draft', 'Sent', 'Paid', 'Overdue', 'Cancelled'])->default('Draft');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('invoices');
    }
};