<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            $table->string('quotation_number')->unique();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->string('project_event')->nullable();
            $table->date('issue_date');
            $table->date('valid_until');
            $table->decimal('sub_total', 12, 2)->default(0);
            $table->decimal('discount_percent', 5, 2)->default(0);
            $table->decimal('vat_percent', 5, 2)->default(18);
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->text('terms')->nullable();
            $table->string('payment_terms')->nullable();
            $table->enum('status', ['Draft', 'Sent', 'Accepted', 'Rejected', 'Expired'])->default('Draft');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('quotations');
    }
};