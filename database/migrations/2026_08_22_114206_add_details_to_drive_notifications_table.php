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
        Schema::table('drive_notifications', function (Blueprint $table) {
            $table->text('message')->nullable()->after('user_id');
            $table->string('link')->nullable()->after('message');
            $table->string('attachment_path')->nullable()->after('link');
            $table->boolean('is_link')->default(false)->after('attachment_path');
            $table->boolean('is_attachment')->default(false)->after('is_link');
            $table->boolean('from_admin')->default(false)->after('is_attachment');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('drive_notifications', function (Blueprint $table) {
            $table->dropColumn([
                'message',
                'link',
                'attachment_path',
                'is_link',
                'is_attachment',
                'from_admin'
            ]);
        });
    }
};
