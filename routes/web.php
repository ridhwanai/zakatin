<?php

use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ZakatCalculatorController;
use App\Http\Controllers\ZakatRecordController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ProjectController::class, 'index'])->name('home');
Route::get('/dashboard', [ProjectController::class, 'index'])->name('dashboard');
Route::redirect('/misi-6/zakat.html', '/kalkulator-zakat', 301);
Route::get('/kalkulator-zakat', [ZakatCalculatorController::class, 'zakatCalculator'])->name('zakat.calculator');

Route::middleware('auth')->group(function () {
    Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
    Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('projects.show');
    Route::patch('/projects/{project}/rates', [ProjectController::class, 'updateRates'])->name('projects.rates.update');
    Route::delete('/projects/{project}', [ProjectController::class, 'destroy'])->name('projects.destroy');
    Route::get('/projects/{project}/pdf', [ProjectController::class, 'pdf'])->name('projects.pdf');
    Route::post('/projects/{project}/records', [ZakatRecordController::class, 'store'])->name('records.store');
    Route::patch('/projects/{project}/records/{record}', [ZakatRecordController::class, 'update'])->name('records.update');
    Route::delete('/projects/{project}/records/{record}', [ZakatRecordController::class, 'destroy'])->name('records.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
