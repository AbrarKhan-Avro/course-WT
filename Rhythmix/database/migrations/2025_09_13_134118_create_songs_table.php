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
    Schema::create('songs', function (Blueprint $table) {
        $table->id();
        $table->foreignId('artist_id')->constrained()->cascadeOnDelete();
        $table->foreignId('album_id')->nullable()->constrained()->nullOnDelete();
        $table->string('title');
        $table->string('file_path'); // where uploaded song is stored
        $table->integer('duration')->nullable(); // seconds
        $table->string('genre')->nullable();
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('songs');
    }
};
