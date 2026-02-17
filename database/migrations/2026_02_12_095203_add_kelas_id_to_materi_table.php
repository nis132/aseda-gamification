<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('materi', function (Blueprint $table) {
            $table->foreignId('kelas_id')->nullable()->after('id')
                  ->constrained('kelas')->onDelete('cascade');
            $table->index('kelas_id');
        });
    }

    public function down()
    {
        Schema::table('materi', function (Blueprint $table) {
            $table->dropForeign(['kelas_id']);
            $table->dropColumn('kelas_id');
        });
    }
};
