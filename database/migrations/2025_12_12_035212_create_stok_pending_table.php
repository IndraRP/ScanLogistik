<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stok_pending', function (Blueprint $table) {
            $table->id();

            // Barang yang diajukan
            $table->unsignedBigInteger('barang_id');
            $table->unsignedInteger('jumlah');

            // Status: pending | approved | rejected
            $table->enum('status', ['pending', 'approved', 'rejected'])
                ->default('pending');

            // User pemohon & user yang approve
            $table->unsignedBigInteger('requested_by')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();

            $table->timestamps();

            // Relasi FK
            $table->foreign('barang_id')->references('id')->on('barang')->cascadeOnDelete();
            $table->foreign('requested_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('approved_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stok_pending');
    }
};
