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
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content')->nullable();
            $table->enum('type', ['announcement', 'notice']);
            $table->enum('priority', ['normal', 'important', 'urgent'])->default('normal');
            $table->string('file_path')->nullable();
            $table->string('external_link')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('publish_date')->useCurrent();
            $table->timestamp('expiry_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};
