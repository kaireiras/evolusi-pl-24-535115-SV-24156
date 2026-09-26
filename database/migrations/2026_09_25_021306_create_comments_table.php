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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();

            // Mereferensikan kolom 'id_blog' pada tabel 'blog'
            $table->foreignId('blog_id')
                ->constrained(table: 'blog', column: 'id_blog')
                ->onDelete('cascade');

            $table->text('isi_balasan');
            $table->string('pengirim')->default('Anonim');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
