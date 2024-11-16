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
        Schema::create('testimonial', function (Blueprint $table) {
            $table->id(); // Primary key, AUTO_INCREMENT
            $table->string('userImage', 255)->nullable(); // VARCHAR(255), NULL
            $table->string('userName', 100)->nullable(); // VARCHAR(100), NULL
            $table->string('designation', 100)->nullable(); // VARCHAR(100), NULL
            $table->string('heading', 255)->nullable(); // VARCHAR(255), NULL
            $table->text('description')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('testimonial');
    }
};
