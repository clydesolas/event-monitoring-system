<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('student_number')->unique();
            $table->string('first_name');
            $table->string('middle_name');
            $table->string('last_name');
            $table->string('course');
            $table->string('sex');
            $table->string('birth_date');
            $table->enum('role', [ 'admin', 'user'])->default('user');
            $table->string('email')->unique();
            $table->string('status')->default('ENROLLED');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        // Insert admin user
        DB::table('users')->insert([
            'student_number' => 'ADMIN',
            'first_name' => 'Admin',
            'middle_name' => 'Admin',
            'last_name' => 'Admin',
            'course' => 'Admin Course',
            'sex' => 'Male', // or 'Female' based on your application
            'birth_date' => '1990-01-01', // Change as needed
            'role' => 'admin',
            'email' => 'admin@gmail.com',
            'status' => 'ENROLLED',
            'password' => Hash::make('password'), // Hash the password
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
