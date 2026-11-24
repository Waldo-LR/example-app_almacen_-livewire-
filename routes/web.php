<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ProductosController;
use App\Livewire\Pages\Productos\Listado;

//Route::get('/', [UsuarioController::class, 'index'])->name('usuarios.index'); 

//Route::resource('/productos', ProductosController::class);

// Ruta para Productos
// Route::livewire('/productos','pages::productos.listados')->name('productos.index');

// // Ruta: 20K Productos 
// Route::livewire('/productos-20k', 'pages::productos.listados-20k')->name('productos.20k');

// // Rutas para Proveedores
// Route::livewire('/proveedores', 'pages::proveedores.listados')->name('proveedores.index');

Route::middleware('guest')->group(function () {
    Route::get('/', fn () => redirect()->route('login'));
    Route::view('/login', 'auth.login')->name('login');
});

Route::middleware('auth')->group(function () {
 Route::view('/panel', 'admin.panel')->name('admin.panel');
// Ruta para Productos
 Route::view('/productos', 'productos.index')->name('productos.web.index');
// Ruta: 20K Productos 
 Route::livewire('/productos-20k', 'pages::productos.listados-20k')->name('productos.20k');
// Rutas para Proveedores
 Route::view('/proveedores', 'proveedores.index')->name('proveedores.index');
});

Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('login');
})->middleware('auth')->name('logout');
