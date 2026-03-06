<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('challenges')) {
            return;
        }

        Schema::create('challenges', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('teacher_id');
            $table->text('hint');
            $table->timestamp('created_at')->useCurrent();

            $table->index('teacher_id');
            $table->foreign('teacher_id')->references('id')->on('user')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('challenges');
    }
};
