<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('photo')->nullable()->after('email');
            $table->string('title')->nullable()->after('photo'); // cargo / título profissional
            $table->text('bio')->nullable()->after('title');
            $table->string('phone')->nullable()->after('bio');
            $table->string('location')->nullable()->after('phone');
            $table->string('linkedin_url')->nullable()->after('location');
            $table->string('github_url')->nullable()->after('linkedin_url');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'photo', 'title', 'bio', 'phone', 'location',
                'linkedin_url', 'github_url',
            ]);
        });
    }
};
