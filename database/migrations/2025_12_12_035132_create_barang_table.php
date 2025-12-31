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
        Schema::create('barang', function (Blueprint $table) {
            $table->id();
            $table->string('nama_barang');
            $table->text('deskripsi')->nullable();
            $table->string('image')->nullable();
            $table->string('kode_barcode')->unique();
            $table->enum('status', ['pending', 'approved'])->default('pending');
            $table->unsignedInteger('stok')->default(0);
            $table->timestamps();

            // Relasi pengesah barang
            $table->unsignedBigInteger('verified_by')->nullable();
            $table->text('note')->nullable();

            $table->foreign('verified_by')
                ->references('id')->on('users')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barang');
    }
};
