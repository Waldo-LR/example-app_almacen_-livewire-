<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Registro de Productos</title>
    @vite('resources/css/app.css')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto grid grid-cols-6 py-10 px-6 rounded-2xl bg-gradient-to-b from-gray-300 to-gray-500 shadow-lg max-w-7xl mt-7"> 
        <div class="col-span-1">
            @yield('menu')
        </div>
        <div class="col-span-3">
            @yield('contenido')
        </div>
        <div class="col-span-2">
            @yield('formulario')
        </div>
    </div>
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