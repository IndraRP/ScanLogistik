<?php

use App\Livewire\Admin\AccBarang;
use App\Livewire\Admin\AllBarang;
use App\Livewire\Admin\EditBarang;
use App\Livewire\Admin\ViewBarang;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\SignUp;
use App\Livewire\BarangExcel;
use App\Livewire\BarangMasuk;
use App\Livewire\CheckBarang;
use App\Livewire\CreateBarang;
use App\Livewire\Home;
use App\Livewire\InputCodeBarang;
use App\Livewire\ScanBarcode;
use App\Livewire\ScanIntro;
use App\Livewire\ScanKeluar;
use App\Livewire\ScanMasuk;
use App\Livewire\Vendor\BuatBarcode;
use Illuminate\Support\Facades\Route;
use Psy\ManualUpdater\Checker;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/



Route::get('/login', Login::class)->name('login');
Route::get('/sign_up', SignUp::class)->name('sign_up');

Route::get('/', Home::class)->name('home');

Route::get('/BarangMasuk', BarangMasuk::class)->name('BarangMasuk');
Route::get('/ScanIntro', ScanIntro::class)->name('ScanIntro');

Route::get('/createbarang', CreateBarang::class)->name('create');
Route::get('/barang-excel', BarangExcel::class)->name('barang-excel');
Route::get('/ScanMasuk', ScanMasuk::class)->name('ScanMasuk');

Route::get('/ScanKeluar', ScanKeluar::class)->name('ScanIntro');

Route::get('/InputCode', InputCodeBarang::class)->name('InputCodeBarang');

Route::get('/AllBarang', AllBarang::class)->name('admin_all');
Route::get('/ViewBarang/{id}', ViewBarang::class)->name('view_barang');
Route::get('/EditBarang/{id}', EditBarang::class)->name('edit_barang');
Route::get('/barang/{id}/export', [ViewBarang::class, 'export'])
    ->name('barang.export');

Route::post('/barang/exportall', [AllBarang::class, 'export'])
    ->name('barang.exportall');


Route::get('/CheckBarang', CheckBarang::class)->name('CheckBarang');
Route::get('/admin_acc', AccBarang::class)->name('admin_acc');

Route::get('/create-barcode', BuatBarcode::class)->name('create-barcode');

Route::get('/download-template', function () {
    return response()->download(
        storage_path('app/public/templates/Book1.xlsx')
    );
})->name('download.excel');
