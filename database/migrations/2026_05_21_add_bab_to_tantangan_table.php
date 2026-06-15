<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('tantangan', function (Blueprint $table) {
            if (!Schema::hasColumn('tantangan', 'bab')) {
                $table->tinyInteger('bab')->unsigned()->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('tantangan', function (Blueprint $table) {
            if (Schema::hasColumn('tantangan', 'bab')) {
                $table->dropColumn('bab');
            }
        });
    }
};
