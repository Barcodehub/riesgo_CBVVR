<?php

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InspectionController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
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

// Route::middleware(['auth'])->group(function () {
//     Route::get('/', function () {
//         return view('app');
//     })->name('app');

//     Route::resource('roles', RoleController::class)->except(['create', 'edit', 'show']);
    
//     Route::resource('users', UserController::class)->except(['create', 'edit', 'show']);
    
//     Route::resource('documents', DocumentController::class)->except(['create', 'edit', 'show']);
    
//     Route::resource('companies', CompanyController::class)->except(['create', 'edit', 'show']);
    
//     Route::resource('inspections', InspectionController::class)->except(['create', 'edit', 'show']);
// });


Route::prefix('admin')->middleware(['admin'])->group(function () {
    Route::get('dashboard', [LoginController::class, 'adminDashboard'])->name('admin.dashboard');

    Route::resources([
        'roles' => RoleController::class,
        'users' => UserController::class,
        'documents' => DocumentController::class,
        'companies' => CompanyController::class,
        'inspections' => InspectionController::class,
    ], ['except' => ['create', 'edit', 'show']]);
});


Route::prefix('inspector')->middleware(['inspector'])->group(function () {
    Route::get('dashboard', [LoginController::class, 'inspectorDashboard'])->name('inspector.dashboard');
    Route::get('inspeccionesAsignadas', [InspectionController::class, 'inspeccionesAsignadas'])->name('inspector.inspeccionesAsignadas');
    
});

Route::prefix('cliente')->middleware(['cliente'])->group(function () {
    Route::get('dashboard', [LoginController::class, 'clienteDashboard'])->name('cliente.dashboard');
    Route::get('datosEmpresa', [CompanyController::class, 'datosEmpresa'])->name('cliente.datosEmpresa');
    
});
