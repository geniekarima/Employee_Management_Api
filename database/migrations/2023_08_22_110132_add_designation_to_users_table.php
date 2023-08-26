<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('designation')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('image')->nullable();
            $table->date('birth_date')->nullable();

            $table->boolean('is_active')->default(true);
            $table->boolean('is_verified')->default(false);

            $table->string('otp')->nullable();
            $table->dateTime('otp_created_at')->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
