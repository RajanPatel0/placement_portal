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
        Schema::create('placement_drive', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->date('drive_date');
            $table->date('application_deadline')->nullable();
            $table->string('location')->nullable();
            $table->string('job_role')->nullable();
            $table->json('course_id')->nullable();
            $table->json('department_id')->nullable();
            $table->boolean('is_reappear')->default(false);
            $table->text('description')->nullable(); // Changed to text for longer content
            $table->string('contact_person')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_phone')->nullable();

            // New fields
            $table->enum('status', ['upcoming', 'ongoing', 'completed', 'cancelled'])->default('upcoming');
            $table->enum('drive_type', ['on_campus', 'off_campus', 'virtual'])->default('on_campus');
            $table->decimal('package_offered', 10, 2)->nullable()->comment('CTC in LPA');
            $table->string('eligibility_cgpa', 5)->nullable()->comment('Minimum CGPA required');
            $table->json('eligibility_passing_year')->nullable();
            $table->string('required_skills')->nullable();
            $table->string('job_title')->nullable();
            $table->integer('vacancies')->nullable();
            $table->string('drive_coordinator')->nullable();
            $table->string('company_website')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('created_by');
            $table->timestamps();

            // Indexes for better performance
            $table->index(['drive_date', 'status']);
            $table->index('company_name');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('placement_drive');
    }
};
