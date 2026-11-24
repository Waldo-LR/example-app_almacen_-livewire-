<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Registro de Productos</title>
    @vite('resources/css/app.css')
    @livewireStyles
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="min-h-screen bg-slate-100 font-sans text-slate-800 antialiased">
    @auth
    <header class="mx-auto mt-6 flex max-w-7xl items-center justify-end gap-4 px-6">
        <span>{{ auth()->user()->name }}</span>
        <form method="POST" action="{{ route('logout') }}">@csrf<button type="submit">Salir</button></form>
    </header>
    @endauth
    <div class="mx-auto mt-7 grid w-full max-w-7xl grid-cols-1 gap-6 rounded-2xl bg-gradient-to-br from-slate-200 via-slate-100 to-slate-200 px-5 py-8 shadow-lg sm:px-8 lg:grid-cols-6 lg:px-10">
        <div class="hidden lg:col-span-1 lg:block">
            @yield('menu')
        </div>
        <div class="col-span-1 lg:col-span-3">
            @yield('contenido')
        </div>
        <div class="col-span-1 lg:col-span-2">
            @yield('formulario')
        </div>
    </div>
    @livewireScripts
    <script>
        window.addEventListener('swal:alert', event => {
            const data = event.detail[0];
            Swal.fire({
                title: data.title,
                text: data.text,
                icon: data.icon,
                confirmButtonText: 'Aceptar',
                confirmButtonColor: '#2563eb'
            });
        });
    </script>
</body>
</html>
