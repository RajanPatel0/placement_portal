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
        Schema::table('placement_applications', function (Blueprint $table) {
            $table->boolean('college_by')->default(false)->after('application_responses');
            $table->boolean('company_by')->default(false)->after('college_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('placement_applications', function (Blueprint $table) {
            $table->dropColumn(['college_by', 'company_by']);
        });
    }
};
