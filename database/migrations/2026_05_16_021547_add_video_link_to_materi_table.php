<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('materi', function (Blueprint $table) {
            $table->string('video_url')->nullable()->after('file_url');       // YouTube embed URL
            $table->string('link_referensi')->nullable()->after('video_url'); // link eksternal
        });
    }

    public function down(): void
    {
        Schema::table('materi', function (Blueprint $table) {
            $table->dropColumn(['video_url', 'link_referensi']);
        });
    }
};
