@extends('layouts.app')

@section('contenido')
    <main class="mx-auto w-full max-w-5xl py-4 sm:py-8">
        <div class="mb-8 text-center sm:mb-10">
            <span class="mb-3 inline-flex items-center gap-2 rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold uppercase tracking-wider text-blue-700">
                <span class="h-2 w-2 rounded-full bg-blue-600"></span>
                Administración
            </span>
            <h1 class="text-3xl font-bold tracking-tight text-slate-900 sm:text-4xl">Panel de administración de Almacen</h1>
            <p class="mx-auto mt-3 max-w-xl text-slate-600">Selecciona el módulo al que deseas acceder.</p>
        </div>

        <div class="grid gap-5 sm:grid-cols-2">
            <a href="{{ route('productos.web.index') }}" class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:border-blue-300 hover:shadow-xl hover:shadow-blue-900/10 focus:outline-none focus:ring-4 focus:ring-blue-500/20 sm:p-8">
                <div class="mb-6 flex h-12 w-12 items-center justify-center rounded-xl bg-blue-100 text-blue-700 transition group-hover:bg-blue-600 group-hover:text-white">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5 12 3l9 4.5-9 4.5-9-4.5Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l9 4.5 9-4.5M3 16.5 12 21l9-4.5" />
                    </svg>
                </div>
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-bold text-slate-900">Productos</h2>
                        <p class="mt-1 text-sm text-slate-500">Consulta y administra el inventario.</p>
                    </div>
                    <svg class="h-5 w-5 shrink-0 text-slate-400 transition group-hover:translate-x-1 group-hover:text-blue-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-6-6 6 6-6 6" />
                    </svg>
                </div>
            </a>

            <a href="{{ route('proveedores.index') }}" class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:border-emerald-300 hover:shadow-xl hover:shadow-emerald-900/10 focus:outline-none focus:ring-4 focus:ring-emerald-500/20 sm:p-8">
                <div class="mb-6 flex h-12 w-12 items-center justify-center rounded-xl bg-emerald-100 text-emerald-700 transition group-hover:bg-emerald-600 group-hover:text-white">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2m6-10a4 4 0 1 0 0-8 4 4 0 0 0 0 8Zm10 10v-2a4 4 0 0 0-3-3.87m-1-11.13a4 4 0 0 1 0 7.75" />
                    </svg>
                </div>
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <h2 class="text-xl font-bold text-slate-900">Proveedores</h2>
                        <p class="mt-1 text-sm text-slate-500">Accede a la gestión de proveedores.</p>
                    </div>
                    <svg class="h-5 w-5 shrink-0 text-slate-400 transition group-hover:translate-x-1 group-hover:text-emerald-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-6-6 6 6-6 6" />
                    </svg>
                </div>
            </a>
        </div>
    </main>
@endsection
