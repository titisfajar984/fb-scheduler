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
        Schema::create('fb_pages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fb_account_id')->constrained()->onDelete('cascade');
            $table->string('page_id');
            $table->string('page_name');
            $table->text('page_access_token');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fb_pages');
    }
};
