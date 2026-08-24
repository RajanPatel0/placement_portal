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
        Schema::table('users_profile', function (Blueprint $table) {
            $table->integer('passing_year')->nullable()->after('department');
            $table->decimal('cgpa', 4, 2)->nullable()->after('passing_year');
            $table->renameColumn('boi', 'bio');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users_profile', function (Blueprint $table) {
            $table->renameColumn('bio', 'boi');
            $table->dropColumn(['passing_year', 'cgpa']);
        });
    }
};
