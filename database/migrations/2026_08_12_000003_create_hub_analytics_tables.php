<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hub_impressions', function (Blueprint $table) {
            $table->id();
            $table->string('source_type', 30);
            $table->string('source_key', 50);
            $table->timestamp('viewed_at');
            $table->index(['source_type', 'source_key', 'viewed_at']);
        });

        Schema::create('hub_page_views', function (Blueprint $table) {
            $table->id();
            $table->timestamp('viewed_at');
            $table->index('viewed_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hub_page_views');
        Schema::dropIfExists('hub_impressions');
    }
};
