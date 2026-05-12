<?php

use App\Exports\ActualExport;
use App\Exports\RabsExport;
use App\Exports\WorkorderAllExport;
use App\Http\Controllers\Actual\IndirectaController;
use App\Http\Controllers\Actual\LuarrabController;
use App\Http\Controllers\Actual\PpnaController;
use App\Http\Controllers\CncController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\dashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Pengajuan\DirectaController;
use App\Http\Controllers\Pengajuan\DirectpController;
use App\Http\Controllers\Pengajuan\IndirectpController;
use App\Http\Controllers\Pengajuan\PpnController;
use App\Http\Controllers\ProjectManagerController;
use App\Http\Controllers\RabController;
use App\Http\Controllers\RabpengajuanController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WorkorderController;
use App\Models\Indirecta;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;

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
Route::get('/workorders/search', [WorkorderController::class, 'search'])->name('workorders.search');
Route::get('/workorders',[WorkorderController::class, 'index'])->name('page.workorder.index');
Route::get('/workorders/create', [WorkorderController::class, 'create'])->name('page.workorder.create');
Route::post('/workorders', [WorkorderController::class, 'store'])->name('page.workorder.store');
Route::get('/workorders/{id}', [WorkorderController::class, 'show'])->name('page.workorder.show');
Route::get('/workorders/edit/{id}', [WorkorderController::class, 'edit'])->name('page.workorder.edit');
Route::put('/workorders/{id}', [WorkorderController::class, 'update'])->name('page.workorder.update');
Route::delete('/workorders/{id}', [WorkorderController::class, 'destroy'])->name('page.workorder.destroy');
Route::post('/workorders/restore//{id}', [WorkorderController::class, 'restore'])->name('page.workorder.restore');
Route::delete('/workorders/force-delete/{id}', [WorkorderController::class, 'forceDelete'])->name('page.workorder.forceDelete');
Route::get('/workorder/{id}/detail', [WorkorderController::class, 'showDetail'])->name('page.workorder.detail');
Route::get('/workorder/{id}/export-pdf', [WorkorderController::class, 'exportPdf'])->name('page.workorder.exportpdf');
Route::get('/workorder/export-all-excel', [WorkorderController::class, 'exportAllExcel'])->name('page.workorder.exportall');


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
 Route::delete('/directp/{id}', [DirectPController::class, 'destroy'])->name('page.Directcost.destroy');
 Route::post('/directp/restore/{id}', [DirectPController::class, 'restore'])->name('page.Directcost.restore');
 Route::delete('/directp/force-delete/{id}', [DirectPController::class, 'forceDelete'])->name('page.Directcost.forceDelete');
 Route::get('/directp.export', [DirectpController::class, 'export'])->name('directp.export');
 Route::get('/check-item/{item_id}', [DirectpController::class, 'checkItem'])->name('check.item');
 Route::get('/directp/import', [DirectpController::class, 'importForm'])->name('directp.import.form');
 Route::post('/directp/import', [DirectpController::class, 'import'])->name('directp.import');
 


 Route::get('/directa',[DirectaController::class, 'index'])->name('page.directa.index');
 Route::get('/directa/create', [DirectaController::class, 'create'])->name('page.directa.create');
 Route::post('/directa/store', [DirectaController::class, 'store'])->name('page.directa.store');
 Route::get('/directa/{id}', [DirectaController::class, 'show'])->name('page.directa.show');
 Route::get('/directa/edit/{id}', [DirectaController::class, 'edit'])->name('page.directa.edit');
 Route::put('/directa/{id}', [DirectaController::class, 'update'])->name('page.directa.update');
 Route::delete('/directa/{id}', [DirectaController::class, 'destroy'])->name('page.directa.destroy');
 Route::post('/directa/restore//{id}', [DirectaController::class, 'restore'])->name('page.directa.restore');
 Route::delete('/directa/force-delete/{id}', [DirectaController::class, 'forceDelete'])->name('page.directa.forceDelete');
 Route::get('/directa.export', [DirectaController::class, 'export'])->name('directa.export');


 Route::get('/indirectp',[IndirectpController::class, 'index'])->name('page.indirectp.index');
 Route::get('/indirectp/create', [IndirectpController::class, 'create'])->name('page.indirectp.create');
 Route::post('/indirectp/store', [IndirectpController::class, 'store'])->name('page.indirectp.store');
 Route::get('/indirectp/{id}', [IndirectpController::class, 'show'])->name('page.indirectp.show');
 Route::get('/indirectp/edit/{id}', [IndirectpController::class, 'edit'])->name('page.indirectp.edit');
 Route::put('/indirectp/{id}', [IndirectpController::class, 'update'])->name('page.indirectp.update');
 Route::delete('/indirectp/{id}', [IndirectpController::class, 'destroy'])->name('page.indirectp.destroy');
 Route::post('/indirectp/restore/{id}', [IndirectpController::class, 'restore'])->name('page.indirectp.restore');
 Route::delete('/indirectp/force-delete/{id}', [IndirectpController::class, 'forceDelete'])->name('page.indirectp.forceDelete');
 Route::get('/indirectp.export', [IndirectpController::class, 'export'])->name('indirectp.export');
 Route::post('/indirectp/import', [IndirectpController::class, 'import'])->name('indirectp.import');

 Route::get('/ppn',[PpnController::class, 'index'])->name('page.ppn.index');
 Route::get('/ppn/create', [PpnController::class, 'create'])->name('page.ppn.create');
 Route::post('/ppn/store', [PpnController::class, 'store'])->name('page.ppn.store');
 Route::get('/ppn/{id}', [PpnController::class, 'show'])->name('page.ppn.show');
 Route::get('/ppn/edit/{id}', [PpnController::class, 'edit'])->name('page.ppn.edit');
 Route::put('/ppn/{id}', [PpnController::class, 'update'])->name('page.ppn.update');
 Route::delete('/ppn/{id}', [PpnController::class, 'destroy'])->name('page.ppn.destroy');
 Route::post('/ppn/restore/{id}', [PpnController::class, 'restore'])->name('page.ppn.restore');
 Route::delete('/ppn/force-delete/{id}', [PpnController::class, 'forceDelete'])->name('page.ppn.forceDelete');
 Route::get('/ppn.export', [PpnController::class, 'export'])->name('ppn.export');
 Route::get('/ppn/import', [PpnController::class, 'importForm'])->name('ppn.import.form');
 Route::post('/ppn/import', [PpnController::class, 'import'])->name('ppn.import');

 Route:: get('/indirecta',[IndirectaController::class, 'index'])->name('page.indirecta.index');
 Route::get('/indirecta/create', [IndirectaController::class, 'create'])->name('page.indirecta.create');
 Route::post('/indirecta/store', [IndirectaController::class, 'store'])->name('page.indirecta.store');
 Route::get('/indirecta/{id}', [IndirectaController::class, 'show'])->name('page.indirecta.show');
 Route::get('/indirecta/edit/{id}', [IndirectaController::class, 'edit'])->name('page.indirecta.edit');
 Route::put('/indirecta/{id}', [IndirectaController::class, 'update'])->name('page.indirecta.update');
 Route::delete('/indirecta/{id}', [IndirectaController::class, 'destroy'])->name('page.indirecta.destroy');
 Route::post('/indirecta/restore//{id}', [IndirectaController::class, 'restore'])->name('page.indirecta.restore');
 Route::delete('/indirecta/force-delete/{id}', [IndirectaController::class, 'forceDelete'])->name('page.indirecta.forceDelete');
 Route::get('/indirecta.export', [IndirectaController::class, 'exports'])->name('page.indirecta.export');

 Route::get('/ppna',[PpnaController::class, 'index'])->name('page.ppna.index');
 Route::get('/ppna/create', [PpnaController::class, 'create'])->name('page.ppna.create');
 Route::post('/ppna/store', [PpnaController::class, 'store'])->name('page.ppna.store');
 Route::get('/ppna/{id}', [PpnaController::class, 'show'])->name('page.ppna.show');
 Route::get('/ppna/edit/{id}', [PpnaController::class, 'edit'])->name('page.ppna.edit');
 Route::put('/ppna/{id}', [PpnaController::class, 'update'])->name('page.ppna.update');
 Route::delete('/ppna/{id}', [PpnaController::class, 'destroy'])->name('page.ppna.destroy');
 Route::post('/ppna/restore//{id}', [PpnaController::class, 'restore'])->name('page.ppna.restore');
 Route::delete('/ppna/force-delete/{id}', [PpnaController::class, 'forceDelete'])->name('page.ppna.forceDelete');
 Route::get('/ppna.export', [PpnaController::class, 'exports'])->name('page.ppna.export');

 Route::get('/luarrab',[LuarrabController::class, 'index'])->name('page.luarrab.index');
 Route::get('/luarrab/create', [LuarrabController::class, 'create'])->name('page.luarrab.create');
 Route::post('/luarrab/store', [LuarrabController::class, 'store'])->name('page.luarrab.store');
 Route::get('/luarrab/{id}', [LuarrabController::class, 'show'])->name('page.luarrab.show');
 Route::get('/luarrab/edit/{id}', [LuarrabController::class, 'edit'])->name('page.luarrab.edit');
 Route::put('/luarrab/{id}', [LuarrabController::class, 'update'])->name('page.luarrab.update');
 Route::delete('/luarrab/{id}', [LuarrabController::class, 'destroy'])->name('page.luarrab.destroy');
 Route::post('/luarrab/restore//{id}', [LuarrabController::class, 'restore'])->name('page.luarrab.restore');
 Route::delete('/luarrab/force-delete/{id}', [LuarrabController::class, 'forceDelete'])->name('page.luarrab.forceDelete');
 Route::get('/luarrab.export', [LuarrabController::class, 'export'])->name('page.luarrab.export');

 Route::get('/report/actual', [ReportController::class, 'index'])->name('report.pengajuan_actual');
 Route::get('/report/actual/{wo}', [ReportController::class, 'getData'])->name('report.actual.data');
 Route::get('/report/export/{workorder_id}', [ReportController::class, 'exportActual'])->name('report.export');

 Route::get('/form/rabindex',[RabpengajuanController::class, 'index'])->name('page.RAB.form.index');
 Route::post('/form/rabstore', [RabpengajuanController::class, 'store'])->name('page.rab.store');
 Route::get('/form/rabedit/{id}', [RabpengajuanController::class, 'edit'])->name('page.rab.edit');
 Route::put('/form/rabupdate/{id}', [RabpengajuanController::class, 'update'])->name('page.rab.update');
 Route::delete('/form/rabdestroy/{id}', [RabpengajuanController::class, 'destroy'])->name('page.rab.destroy');
 Route::post('/form/rabrestore/{id}', [RabpengajuanController::class, 'restore'])->name('page.rab.restore');
 Route::delete('/form/rabforcedelete/{id}', [RabpengajuanController::class, 'forceDelete'])->name('page.rab.forceDelete');
 Route::get('/form/rabexport', [RabpengajuanController::class, 'export'])->name('page.rab.export');

 Route::get('/approval/rabindex', [RabpengajuanController::class, 'indexApproval'])->name('page.RAB.approval.index');
 Route::get('/approval/payment/{id}', [RabpengajuanController::class, 'indexpayment'])->name('page.RAB.approval.payment');
 Route::get('/approval/reject/{id}', [RabpengajuanController::class, 'indexreject'])->name('page.RAB.approval.reject');
 Route::get('/approval/partial/{id}', [RabpengajuanController::class, 'indexpartial'])->name('page.RAB.approval.partial');
 Route::post('/approval/rabapprove/{id}', [RabpengajuanController::class, 'approve'])->name('page.rab.approve');
 Route::post('/approval/rabreject/{id}', [RabpengajuanController::class, 'reject'])->name('page.rab.reject');
 Route::post('/approval/rabpartial/{id}', [RabpengajuanController::class, 'partial'])->name('page.rab.partial');
 Route::get('/summary', [RabpengajuanController::class, 'summary'])->name('page.RAB.summary.index');
 Route::post('/rab/approval/import',[RabPengajuanController::class, 'import'])->name('page.RAB.approval.import');

 Route::get('/monitoring-cnc', [CncController::class, 'page'])->name('cnc.page');

// ================= PROJECT MANAGER =================

// LIST PROJECT
Route::get(
    '/projectmanager',
    [ProjectManagerController::class, 'index']
)->name('page.Projectmanager.index');


// CREATE PROJECT
Route::get(
    '/projectmanager/create',
    [ProjectManagerController::class, 'createpm']
)->name('page.projectmanager.create');


// STORE PROJECT
Route::post(
    '/projectmanager/store',
    [ProjectManagerController::class, 'store']
)->name('projectmanager.store');


Route::get(
    '/project-detail/{id}',
    [ProjectManagerController::class, 'detail']
)->name('project.detail');


// EDIT PROJECT
Route::get(
    '/projectmanager/{id}/edit',
    [ProjectManagerController::class, 'edit']
)->name('projectmanager.edit');


// UPDATE PROJECT
Route::put(
    '/projectmanager/{id}',
    [ProjectManagerController::class, 'update']
)->name('projectmanager.update');


// STORE TASK
Route::post(
    '/projectmanager/task/store',
    [ProjectManagerController::class, 'storeTask']
)->name('projectmanager.task.store');


// DASHBOARD
Route::get(
    '/projectdashboard',
    [ProjectManagerController::class, 'dashboard']
)->name('projectdashboard.index');



   
