<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // $validator = Validator::make(['is_active' => ''], [
        //     'is_active' => 'required|unique:students',
        // ]);

        // if ($validator->fails()) {
        //     throw new \Exception("Column is_actve have already exists in table students");
        // }
        
        // Schema::table('students', function (Blueprint $table) {
        //     $table->dropColumn('is_active')->default(true);
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }
};
