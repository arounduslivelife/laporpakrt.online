<?php

use Illuminate\Support\Facades\Route;

use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;

use App\Livewire\Dashboard;
use App\Livewire\WargaIndex;
use App\Livewire\TamuIndex;
use App\Livewire\OcrTest;

Route::get('/', function () {
    return view('welcome');
});

Route::any('/testpath', function () {
    return request()->path();
});

Route::post('/testpost', function () {
    return 'testpost';
});

Route::get('/login', Login::class)->name('login');
Route::get('/register', Register::class)->name('register');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', \App\Livewire\Dashboard::class)->name('dashboard');
    Route::get('/warga', \App\Livewire\WargaIndex::class)->name('warga.index');
    Route::get('/warga/export/excel', [\App\Http\Controllers\WargaExportController::class, 'excel'])->name('warga.export.excel');
    Route::get('/warga/export/pdf', [\App\Http\Controllers\WargaExportController::class, 'pdf'])->name('warga.export.pdf');
    Route::get('/tamu', \App\Livewire\TamuIndex::class)->name('tamu.index');
    Route::get('/ocr-test', \App\Livewire\OcrTest::class)->name('ocr.test');
    Route::get('/surat', \App\Livewire\SuratIndex::class)->name('surat.index');
    Route::get('/surat/create', \App\Livewire\SuratCreate::class)->name('surat.create');
    Route::get('/surat/{id}/pdf', [\App\Http\Controllers\SuratController::class, 'pdf'])->name('surat.pdf');
    Route::get('/kas', \App\Livewire\KasIndex::class)->name('kas.index');
    Route::get('/users', \App\Livewire\UserManagement::class)->name('users.index');
    Route::post('/logout', function() { \Illuminate\Support\Facades\Auth::logout(); session()->regenerateToken(); return redirect('/'); })->name('logout');
});
