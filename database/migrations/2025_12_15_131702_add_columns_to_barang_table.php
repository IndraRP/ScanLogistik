<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('barang', function (Blueprint $table) {
            // Kolom yang sudah ada: id, nama_barang, deskripsi, image, kode_barcode, 
            // status, stok, created_at, updated_at, verified_by, note, location

            // Menambahkan kolom baru sesuai dengan tabel di gambar
            $table->string('stock_code', 50)->nullable()->after('id');
            $table->string('part_number', 100)->nullable()->after('stock_code');
            $table->string('mnemonic', 50)->nullable()->after('part_number');
            $table->string('warehouse', 100)->nullable()->after('location'); // Menambahkan warehouse baru
            $table->string('uom', 20)->nullable()->after('warehouse'); // Unit of Measure (UOI di gambar)
            $table->integer('qty')->unsigned()->default(0)->after('uom');
            $table->integer('soh_odoo')->unsigned()->default(0)->after('qty'); // Stock on Hand ODOO
            $table->integer('outstanding_belum_wr')->unsigned()->default(0)->nullable()->after('soh_odoo');
            $table->integer('difference')->default(0)->nullable()->after('outstanding_belum_wr');
            $table->text('remarks')->nullable()->after('difference');
        });
    }

    public function down(): void
    {
        Schema::table('barang', function (Blueprint $table) {
            $table->dropColumn([
                'stock_code',
                'part_number',
                'mnemonic',
                'uom',
                'qty',
                'soh_odoo',
                'outstanding_belum_wr',
                'difference',
                'remarks'
            ]);
        });
    }
};
