<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ProductosController;
use App\Livewire\Pages\Productos\Listado;

//Route::get('/', [UsuarioController::class, 'index'])->name('usuarios.index'); 

//Route::resource('/productos', ProductosController::class);

// Ruta 1: Versión básica (solo buscador)
Route::livewire('/productos','pages::productos.listados')->name('productos.index');

// Ruta 2: Versión completa (buscador + desplegables de categoría y almacén)
Route::livewire('/productos-20k', 'pages::productos.listados-20k')->name('productos.20k');