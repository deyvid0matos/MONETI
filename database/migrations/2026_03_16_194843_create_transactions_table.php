<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->enum('type', ['income', 'expense']);
            $table->string('description');
            $table->decimal('amount', 12, 2);

            $table->date('due_date');

            $table->enum('repeat_type', ['none', 'always', 'installment'])->default('none');
            $table->enum('frequency', ['monthly'])->nullable();

            $table->unsignedInteger('installments_total')->nullable();
            $table->unsignedInteger('installment_number')->nullable();

            $table->unsignedBigInteger('parent_transaction_id')->nullable();

            $table->timestamps();

            $table->foreign('parent_transaction_id')
                ->references('id')
                ->on('transactions')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};