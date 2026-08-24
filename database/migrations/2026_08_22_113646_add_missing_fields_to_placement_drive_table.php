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
        Schema::table('placement_drive', function (Blueprint $table) {
            $table->json('college_id')->nullable()->after('job_role');
            $table->unsignedBigInteger('company_user_id')->default(0)->after('created_by');
            $table->decimal('tenth_percentage', 5, 2)->nullable()->after('company_user_id');
            $table->decimal('twelfth_percentage', 5, 2)->nullable()->after('tenth_percentage');
            $table->decimal('graduation_percentage', 5, 2)->nullable()->after('twelfth_percentage');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('placement_drive', function (Blueprint $table) {
            $table->dropColumn([
                'college_id',
                'company_user_id',
                'tenth_percentage',
                'twelfth_percentage',
                'graduation_percentage'
            ]);
        });
    }
};
