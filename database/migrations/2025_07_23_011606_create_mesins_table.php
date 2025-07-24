<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('mesins', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('kodeMesin')->unique();
            $table->string('name');
            $table->string('kapasitas')->nullable();
            $table->string('speed')->nullable();
            $table->integer('jumlahOperator')->default(2);
            $table->string('inupby');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mesin');
    }
};
