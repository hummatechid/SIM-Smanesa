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
        Schema::create('penggunas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('nik');
            $table->string('full_name');
            $table->enum('gender', ['Laki-laki', 'Perempuan']);
            $table->string('phone_number');
            $table->string('address')->nullable();
            $table->string('religion', 50);
            $table->text('bio')->nullable();
            $table->string('photo')->nullable();
            $table->dateTime('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penggunas');
    }
};
