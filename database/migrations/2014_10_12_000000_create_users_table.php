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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username');
            $table->string('email')->unique();
            $table->string('password');
            //$table->string('role')->default('employee');
            $table->enum('usertype', ['employee', 'owner'])->default('employee');
            $table->rememberToken();
            $table->timestamps();
        });
        DB::table('users')->insert(
            array(
                array(
                    'username' => 'owner',
                    'email' => 'owner@gmail.com',
                    'password' => '$2y$10$96EUB6HsVNFLgrtUqpBqOeR3Z9S2q2.TAnT5TyLcZ47YNMjqZYY7C',
                    'usertype' => 'owner',
                    'created_at' => '2023-08-08 01:42:51',
                    'updated_at' => '2023-08-08 01:42:51'
                )
            )
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
