<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ConceptController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ExtintorController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InspectionController;
use App\Http\Controllers\KitController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EstablecimientoController;
use Illuminate\Support\Facades\Route;


Route::view('/login', 'login')->name('login')->middleware('guest');
Route::view('/registro', 'register')->name('registro')->middleware('guest');

Route::post('/validar-registro', [LoginController::class, 'register'])->name('validar-registro')->middleware('guest');
Route::post('/inicia-sesion', [LoginController::class, 'login'])->name('inicia-sesion')->middleware('guest');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');


Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/admin', [HomeController::class, 'index'])->name('home');
Route::get('/inspector', [HomeController::class, 'index'])->name('home');
Route::get('/cliente', [HomeController::class, 'index'])->name('home');


Route::prefix('admin')->middleware(['admin'])->group(function () {
    Route::get('dashboard', [LoginController::class, 'adminDashboard'])->name('admin.dashboard');

    Route::resources([
        'extintores' => ExtintorController::class,
        'kits' => KitController::class,
        'users' => UserController::class,
        'documents' => DocumentController::class,
        'companies' => CompanyController::class,
    ], ['except' => ['create', 'edit', 'show']]);

    Route::patch('changeState/{id}', [UserController::class, 'changeState'])->name('users.changeState');

    Route::resource('roles', RoleController::class)->only(['index', 'destroy']);
    Route::resource('inspections', InspectionController::class)->only(['index', 'store', 'update', 'asignarInspector', 'destroy']);
    Route::patch('asignarInspector/{id}', [InspectionController::class, 'asignarInspector'])->name('inspections.asignarInspector');
    Route::post('establecimiento/{id}', [EstablecimientoController::class, 'store'])->name('establecimiento.store');
});


Route::prefix('inspector')->middleware(['inspector'])->group(function () {
    Route::get('dashboard', [LoginController::class, 'inspectorDashboard'])->name('inspector.dashboard');
    Route::get('inspeccionesAsignadas', [InspectionController::class, 'inspeccionesAsignadas'])->name('inspector.inspeccionesAsignadas');
    Route::get('inspeccionesRealizadas', [InspectionController::class, 'inspeccionesRealizadas'])->name('inspector.inspeccionesRealizadas');

    Route::post('store/{id}', [ConceptController::class, 'store'])->name('inspector.store');
    Route::patch('finalizar/{id}', [InspectionController::class, 'finalizar'])->name('inspector.finalizar');
});

Route::prefix('cliente')->middleware(['cliente'])->group(function () {
    Route::get('dashboard', [LoginController::class, 'clienteDashboard'])->name('cliente.dashboard');
    Route::get('datosEmpresa', [CompanyController::class, 'datosEmpresa'])->name('cliente.datosEmpresa');
    Route::get('detalleInspeccion', [InspectionController::class, 'showInspections'])->name('cliente.detalleInspeccion');
    Route::post('inspeccion', [InspectionController::class, 'store'])->name('cliente.storeInspeccion');
    Route::post('storeCliente', [CompanyController::class, 'storeCliente'])->name('cliente.storeCliente');
    Route::patch('updateCliente/{id}', [CompanyController::class, 'updateCliente'])->name('cliente.updateCliente');
    Route::post('establecimiento/{id}', [EstablecimientoController::class, 'storeCliente'])->name('cliente.storeEstablecimiento');
});
