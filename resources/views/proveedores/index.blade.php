@extends('layouts.app')

@section('contenido')
    <div class="w-full min-w-0">
        <div class="mb-6">
            <a href="{{ route('admin.panel') }}" class="text-sm font-medium text-blue-700 hover:text-blue-900">← Volver al panel</a>
            <h1 class="mt-3 text-2xl font-bold text-slate-900">Proveedores</h1>
            <p class="mt-1 text-sm text-slate-600">Consulta y administra los proveedores del almacén.</p>
        </div>
        @livewire('proveedores-index')
    </div>
@endsection

@section('formulario')
    @livewire('proveedores-form')
@endsection
