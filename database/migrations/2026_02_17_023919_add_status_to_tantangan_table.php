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
        $table->enum('status', ['draft', 'published'])->default('draft')->after('tipe');
    });
}

public function down()
{
    Schema::table('tantangan', function (Blueprint $table) {
        $table->dropColumn('status');
    });
}

};
