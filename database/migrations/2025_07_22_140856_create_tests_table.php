<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tests', function (Blueprint $table) {
           $table->id();
           $table->string('test_name');
           $table->decimal('price', 8, 2)->nullable(); 
           $table->timestamps();
});
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('tests');
        Schema::enableForeignKeyConstraints();
    }
};
