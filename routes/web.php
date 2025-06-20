<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['auth'])->group(function () {
    Route::resource('forms', App\Http\Controllers\FormController::class);
    Route::resource('fields', App\Http\Controllers\FieldController::class);
    Route::get('/forms/{form}/fields/create', [App\Http\Controllers\FieldController::class, 'create'])->name('fields.create');
    Route::post('/forms/{form}/fields', [App\Http\Controllers\FieldController::class, 'store'])->name('fields.store');
});