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
        Schema::create('students', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nik');
            $table->string('nisn');
            $table->string('nipd');
            $table->string('full_name');
            $table->enum('gender', ['Laki-laki', 'Perempuan']);
            $table->string('phone_number');
            $table->string('address');
            $table->string('religion', 50);
            $table->string('nama_rombel');
            $table->integer('violation_score');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
