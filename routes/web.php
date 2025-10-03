<?php

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\dashboardController;
use App\Http\Controllers\Actual\PpnaController;
use App\Http\Controllers\Pengajuan\PpnController;
use App\Http\Controllers\Actual\LuarrabController;
use App\Http\Controllers\Actual\IndirectaController;
use App\Http\Controllers\Pengajuan\DirectaController;
use App\Http\Controllers\Pengajuan\DirectpController;
use App\Http\Controllers\Pengajuan\IndirectpController;

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

Route::get('/', function () {
    return view('auth.login2');
});
Route::middleware(['auth', 'can:isAdmin'])->group(function() {
    Route::get('/user', [UserController::class, 'index'])->name('user');
    Route::get('/table', [TableController::class, 'index'])->name('table.index');
});

Auth::routes();
Route::middleware(['auth'])->group(function(){
Route::get('/user', [UserController::class, 'index'])->name('user');
Route::get('/table', [TableController::class, 'index'])->name('table.index');
Route::get('/table/create', [TableController::class, 'create'])->name('table.create');
Route::post('/table', [TableController::class, 'store'])->name('table.store');
Route::get('/table/{user}/edit', [TableController::class, 'edit'])->name('table.edit');
Route::put('/table/{user}', [TableController::class, 'update'])->name('table.update');
Route::delete('/table/{user}', [TableController::class, 'destroy'])->name('table.destroy');
Route::get('/home', [dashboardController::class, 'index'])->name('home');
 });

Route::middleware(['guest'])->group(function(){
    Route::get('/login2', [dashboardController::class, 'login2'])->name('login2');
    Route::get('/register2', [dashboardController::class, 'register2'])->name('register2');
 });

 Route::get('/directp',[DirectpController::class, 'index'])->name('page.pengajuan.Directcost.index');
 Route::get('/directp/create', [DirectpController::class, 'create'])->name('page.pengajuan.Directcost.create');
 Route::post('/directp/store', [DirectpController::class, 'store'])->name('page.pengajuan.Directcost.store');
 Route::get('/directp/{id}', [DirectpController::class, 'show'])->name('page.directp.show');
 Route::get('/directp/edit/{id}', [DirectpController::class, 'edit'])->name('page.pengajuan.Directcost.edit');
 Route::put('/directp/{id}', [DirectpController::class, 'update'])->name('page.Directcost.update');
 Route::delete('/directp/delete/{id}', [DirectpController::class, 'destroy'])->name('page.Directcost.destroy');
 Route::get('/directp.export', [DirectpController::class, 'export'])->name('directp.export');


 Route::get('/directa',[DirectaController::class, 'index'])->name('page.directa.index');
 Route::get('/directa/create', [DirectaController::class, 'create'])->name('page.directa.create');
 Route::post('/directa/store', [DirectaController::class, 'store'])->name('page.directa.store');
 Route::get('/directa/{id}', [DirectaController::class, 'show'])->name('page.directa.show');
 Route::get('/directa/edit/{id}', [DirectaController::class, 'edit'])->name('page.directa.edit');
 Route::put('/directa/{id}', [DirectaController::class, 'update'])->name('page.directa.update');
 Route::delete('/directa/delete/{id}', [DirectaController::class, 'destroy'])->name('page.directa.destroy');
 Route::get('/directa.export', [DirectaController::class, 'export'])->name('directa.export');


 Route::get('/indirectp',[IndirectpController::class, 'index'])->name('page.indirectp.index');
 Route::get('/indirectp/create', [IndirectpController::class, 'create'])->name('page.indirectp.create');
 Route::post('/indirectp/store', [IndirectpController::class, 'store'])->name('page.indirectp.store');
 Route::get('/indirectp/{id}', [IndirectpController::class, 'show'])->name('page.indirectp.show');
 Route::get('/indirectp/edit/{id}', [IndirectpController::class, 'edit'])->name('page.indirectp.edit');
 Route::put('/indirectp/{id}', [IndirectpController::class, 'update'])->name('page.indirectp.update');
 Route::delete('/indirectp/delete/{id}', [IndirectpController::class, 'destroy'])->name('page.indirectp.destroy');
 Route::get('/indirectp.export', [IndirectpController::class, 'export'])->name('indirectp.export');

 Route::get('/ppn',[PpnController::class, 'index'])->name('page.ppn.index');
 Route::get('/ppn/create', [PpnController::class, 'create'])->name('page.ppn.create');
 Route::post('/ppn/store', [PpnController::class, 'store'])->name('page.ppn.store');
 Route::get('/ppn/{id}', [PpnController::class, 'show'])->name('page.ppn.show');
 Route::get('/ppn/edit/{id}', [PpnController::class, 'edit'])->name('page.ppn.edit');
 Route::put('/ppn/{id}', [PpnController::class, 'update'])->name('page.ppn.update');
 Route::delete('/ppn/delete/{id}', [PpnController::class, 'destroy'])->name('page.ppn.destroy');
 Route::get('/ppn.export', [PpnController::class, 'export'])->name('ppn.export');

 Route:: get('/indirecta',[IndirectaController::class, 'index'])->name('page.indirecta.index');
 Route::get('/indirecta/create', [IndirectaController::class, 'create'])->name('page.indirecta.create');
 Route::post('/indirecta/store', [IndirectaController::class, 'store'])->name('page.indirecta.store');
 Route::get('/indirecta/{id}', [IndirectaController::class, 'show'])->name('page.indirecta.show');
 Route::get('/indirecta/edit/{id}', [IndirectaController::class, 'edit'])->name('page.indirecta.edit');
 Route::put('/indirecta/{id}', [IndirectaController::class, 'update'])->name('page.indirecta.update');
 Route::delete('/indirecta/delete/{id}', [IndirectaController::class, 'destroy'])->name('page.indirecta.destroy');
 Route::get('/indirecta.export', [IndirectaController::class, 'exports'])->name('page.indirecta.export');

 Route::get('/ppna',[PpnaController::class, 'index'])->name('page.ppna.index');
 Route::get('/ppna/create', [PpnaController::class, 'create'])->name('page.ppna.create');
 Route::post('/ppna/store', [PpnaController::class, 'store'])->name('page.ppna.store');
 Route::get('/ppna/{id}', [PpnaController::class, 'show'])->name('page.ppna.show');
 Route::get('/ppna/edit/{id}', [PpnaController::class, 'edit'])->name('page.ppna.edit');
 Route::put('/ppna/{id}', [PpnaController::class, 'update'])->name('page.ppna.update');
 Route::delete('/ppna/delete/{id}', [PpnaController::class, 'destroy'])->name('page.ppna.destroy');
 Route::get('/ppna.export', [PpnaController::class, 'exports'])->name('page.ppna.export');


 Route::get('/luarrab',[LuarrabController::class, 'index'])->name('page.luarrab.index');
 Route::get('/luarrab/create', [LuarrabController::class, 'create'])->name('page.luarrab.create');
 Route::post('/luarrab/store', [LuarrabController::class, 'store'])->name('page.luarrab.store');
 Route::get('/luarrab/{id}', [LuarrabController::class, 'show'])->name('page.luarrab.show');
 Route::get('/luarrab/edit/{id}', [LuarrabController::class, 'edit'])->name('page.luarrab.edit');
 Route::put('/luarrab/{id}', [LuarrabController::class, 'update'])->name('page.luarrab.update');
 Route::delete('/luarrab/delete/{id}', [LuarrabController::class, 'destroy'])->name('page.luarrab.destroy');
 Route::get('/luarrab.export', [LuarrabController::class, 'export'])->name('page.luarrab.export');
