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
        Schema::create('placement_applications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('placement_drive_id');
            $table->unsignedBigInteger('student_id');
            $table->enum('application_status', ['applied', 'shortlisted', 'rejected', 'selected'])->default('applied');
            $table->json('application_responses')->nullable(); // For custom application questions
            $table->timestamp('applied_at')->useCurrent();
            $table->timestamp('shortlisted_at')->nullable();
            $table->timestamp('selected_at')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('placement_applications');
    }
};
