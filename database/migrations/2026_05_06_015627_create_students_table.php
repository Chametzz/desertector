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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('person_id')->constrained('people')->onDelete('cascade');
            $table->string('control_number', 20)->unique();
            $table->foreignId('major_id')->constrained();
            $table->decimal('gpa', 5, 2);
            $table->foreignId('tutor_id')->nullable()->constrained('tutors');
            $table->enum('status', ['enrolled', 'on_leave', 'graduated', 'dropped_out']);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
