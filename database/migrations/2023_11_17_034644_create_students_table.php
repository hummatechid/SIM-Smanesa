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
            $table->string('email');
            $table->string('nik');
            $table->string('nisn');
            $table->string('nipd');
            $table->string('full_name');
            $table->enum('gender', ['Laki-laki', 'Perempuan']);
            $table->string('phone_number');
            $table->string('home_phone_number')->nullable();
            $table->string('image')->nullable();
            $table->string('address');
            $table->string('religion', 50);
            $table->string('nama_rombel');
            $table->integer('violation_score')->default(0);
            $table->integer('score')->default(0);
            $table->string('register_type');
            $table->string('school_origin');
            $table->string('birth_location');
            $table->string('birth_date');

            $table->string('father_name');
            $table->string('father_job');
            $table->string('mother_name');
            $table->string('mother_job');
            $table->string('guardian_name')->nullable();
            $table->string('guardian_job')->nullable();

            $table->integer('anak_keberapa');
            $table->integer('height');
            $table->integer('weight');
            $table->string('semester');
            $table->integer('tingkat_pendidikan');
            $table->string('kurikulum');
            $table->string('kebutuhan_khusus');

            $table->dateTime('deleted_at')->nullable();
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
