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
    // Update all NULL ages to 1 before enforcing NOT NULL constraint
    \DB::table('patients')->whereNull('age')->update(['age' => 1]);

    Schema::table('patients', function (Blueprint $table) {
        $table->integer('age')->nullable(false)->default(null)->change();
    });
}

public function down()
{
    Schema::table('patients', function (Blueprint $table) {
        $table->integer('age')->nullable()->default(null)->change();
    });
}

};
