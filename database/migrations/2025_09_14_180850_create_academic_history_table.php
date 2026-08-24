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
        Schema::create('academic_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
             $table->enum('education_level', [
                '10th',
                '12th',
                'diploma',
                'bachelor',
                'master',
                'phd',
                'other'
            ]);
            $table->string('institution_name');
            $table->string('degree')->nullable();
            $table->string('field_of_study')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->enum('grade_type', ['gpa', 'percentage'])->nullable();
            $table->decimal('grade', 5, 2)->nullable(); // GPA or percentage 
            $table->string('description')->nullable();
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('academic_history');
    }
};
