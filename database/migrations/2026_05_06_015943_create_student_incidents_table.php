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
        Schema::create('student_incidents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained();
            $table->foreignId('teacher_id')->nullable()->constrained();
            $table->foreignId('tutor_id')->nullable()->constrained();
            $table->foreignId('subject_id')->nullable()->constrained();
            $table->foreignId('category_id')->nullable()->constrained('incident_categories');
            $table->integer('risk_level')->unsigned()->between(1, 3);
            $table->text('description');
            $table->date('date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_incidents');
    }
};
