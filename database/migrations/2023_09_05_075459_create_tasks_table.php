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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('project_id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('dependency')->nullable();
            $table->text('delay_reason')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', ['backlog', 'to_do', 'on_going', 'ready_for_qa', 're-do', 'completed'])->default('to_do');
            $table->timestamps();
            $table->foreign('project_id')
              ->references('id')
              ->on('projects')
              ->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
