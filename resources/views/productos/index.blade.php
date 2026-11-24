@extends('layouts.app')

@section('contenido')
    <div class="w-full min-w-0">
        <div class="mb-6">
            <a href="{{ route('admin.panel') }}" class="text-sm font-medium text-blue-700 hover:text-blue-900">← Volver al panel</a>
            <h1 class="mt-3 text-2xl font-bold text-slate-900">Productos</h1>
            <p class="mt-1 text-sm text-slate-600">Consulta, filtra y administra el inventario.</p>
        </div>
        @livewire('productos-index')
    </div>
@endsection

@section('formulario')
    @livewire('productos-form')
@endsection
