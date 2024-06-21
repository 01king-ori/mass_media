<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MediaController;

Route::get('media', [MediaController::class, 'index'])->name('media.index');
Route::post('media', [MediaController::class, 'store'])->name('media.store');
// ...

Route::get('folder/{folder}/edit', [MediaController::class, 'edit'])->name('media.edit')->where('folder', '.*');
Route::put('/folder/{folder}', [MediaController::class, 'update'])->name('media.update')->where('folder', '.*');

Route::get('/', function () {
    return view('welcome');
});
