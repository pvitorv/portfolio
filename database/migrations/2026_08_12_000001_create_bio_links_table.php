<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bio_links', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('url', 2048);
            $table->string('type', 20)->default('url'); // url, whatsapp, email, phone
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('click_count')->default(0);
            $table->timestamp('last_clicked_at')->nullable();
            $table->timestamps();
        });

        Schema::create('bio_link_clicks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bio_link_id')->constrained('bio_links')->cascadeOnDelete();
            $table->timestamp('clicked_at');
            $table->index(['bio_link_id', 'clicked_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bio_link_clicks');
        Schema::dropIfExists('bio_links');
    }
};
