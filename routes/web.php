<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocToPdfController;
use App\Http\Controllers\WordToPdfController;

Route::get('/', function () {
    return view('wordtopdf');
});

Route::post('/convert', [WordToPdfController::class, 'convert'])->name('convert.word-to-pdf');

