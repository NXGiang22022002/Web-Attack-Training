<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('teacher_id'); 

            $table->string('title', 255);
            $table->text('description')->nullable();

            $table->string('file_path');      
            $table->string('file_name');      
            $table->unsignedBigInteger('file_size')->nullable();

            $table->timestamp('due_at')->nullable();
            $table->timestamps();

            $table->index('teacher_id');
            $table->foreign('teacher_id')->references('id')->on('user')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};