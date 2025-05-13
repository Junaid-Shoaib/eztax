<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\NoticeController;
use App\Http\Controllers\CalculatorController;
use App\Http\Controllers\FileExplorerController;
use Illuminate\Support\Facades\Auth;

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


Route::get('clients', [ClientController::class, 'index'])
    ->name('clients')->middleware('auth');

Route::get('clients/create', [ClientController::class, 'create'])
    ->name('clients.create')->middleware('auth');

Route::post('clients', [ClientController::class, 'store'])
    ->name('clients.store')->middleware('auth');

Route::get('clients/{client}/edit', [ClientController::class, 'edit'])
    ->name('clients.edit')->middleware('auth');

Route::put('clients/{client}', [ClientController::class, 'update'])
    ->name('clients.update')->middleware('auth');

Route::delete('clients/{client}', [ClientController::class, 'destroy'])
    ->name('clients.destroy')->middleware('auth');


Route::get('notices', [NoticeController::class, 'index'])
    ->name('notices')->middleware('auth');

Route::get('notices/create', [NoticeController::class, 'create'])
    ->name('notices.create')->middleware('auth');

Route::post('notices', [NoticeController::class, 'store'])
    ->name('notices.store')->middleware('auth');

Route::get('notices/{notice}/edit', [NoticeController::class, 'edit'])
    ->name('notices.edit')->middleware('auth');

Route::put('notices/{notice}', [NoticeController::class, 'update'])
    ->name('notices.update')->middleware('auth');

Route::put('notices/change/{notice}', [NoticeController::class, 'change'])
    ->name('notices.change')->middleware('auth');

Route::delete('notices/{notice}', [NoticeController::class, 'destroy'])
    ->name('notices.destroy')->middleware('auth');

Route::get('/calculator', [CalculatorController::class, 'index'])
    ->name('calculator')->middleware('auth');

Route::post('/calculate-tax', [CalculatorController::class, 'calculate'])
    ->name('calculate.tax')->middleware('auth');


Route::get('/file-manager/{folder?}', [FileExplorerController::class, 'index'])
    ->where('folder', '.*')->name('file.manager');
Route::get('/file-managers/download/{path}', [FileExplorerController::class, 'download'])
    ->where('path', '.*')->name('file.download');
Route::delete('/file-managers/delete', [FileExplorerController::class, 'delete'])
    ->name('file.delete');