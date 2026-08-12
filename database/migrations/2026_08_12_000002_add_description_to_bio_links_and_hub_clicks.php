<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bio_links', function (Blueprint $table) {
            $table->text('description')->nullable()->after('title');
        });

        Schema::create('hub_clicks', function (Blueprint $table) {
            $table->id();
            $table->string('source_type', 30); // project, profile
            $table->string('source_key', 50);
            $table->timestamp('clicked_at');
            $table->index(['source_type', 'source_key', 'clicked_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hub_clicks');

        Schema::table('bio_links', function (Blueprint $table) {
            $table->dropColumn('description');
        });
    }
};
