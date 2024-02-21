<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id');
            $table->string('event_id');
            $table->string('title');
            $table->string('fullname');
            $table->string('student_number');
            $table->string('paid_by');
            $table->string('relation');
            $table->string('payment');
            $table->string('date');
            $table->string('duration_date');
            $table->string('semester');
            $table->string('academic_year');
            $table->string('status')->default('ACTIVE');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
