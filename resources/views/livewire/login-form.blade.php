<div class="mx-auto w-full max-w-md">
    <div class="mb-8 text-center">
        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-2xl bg-blue-600 text-white shadow-lg shadow-blue-600/25">
            <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 7.5 12 3l9 4.5-9 4.5-9-4.5Z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l9 4.5 9-4.5M3 16.5 12 21l9-4.5" />
            </svg>
        </div>
        <h1 class="text-2xl font-bold tracking-tight text-slate-900">Bienvenido</h1>
        <p class="mt-2 text-sm text-slate-500">Ingresa a tu cuenta para continuar</p>
    </div>

    <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-xl shadow-slate-900/5 sm:p-8">
        @if ($segundosRestantes > 0)
            <div
                wire:poll.1s="actualizarCuentaRegresiva"
                role="status"
                aria-live="polite"
                class="mb-6 flex items-start gap-3 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700"
            >
                <svg class="mt-0.5 h-5 w-5 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                    <circle cx="12" cy="12" r="9" />
                    <path stroke-linecap="round" d="M12 8v4m0 4h.01" />
                </svg>
                <p>
                    @error('email') <span class="font-semibold">{{ $message }}</span> @enderror
                    <span>Podrás intentarlo nuevamente en</span>
                    <span class="font-bold tabular-nums">{{ $segundosRestantes }} {{ $segundosRestantes === 1 ? 'segundo' : 'segundos' }}</span>.
                </p>
            </div>
        @endif

        <form wire:submit="ingresar" class="space-y-5">
            <div>
                <label for="email" class="mb-1.5 block text-sm font-medium text-slate-700">Correo electrónico</label>
                <input
                    id="email"
                    type="email"
                    wire:model="email"
                    placeholder="nombre@ejemplo.com"
                    autocomplete="username"
                    @disabled($segundosRestantes > 0)
                    class="block w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/15 disabled:cursor-not-allowed disabled:bg-slate-100 disabled:text-slate-500"
                >
                @if ($segundosRestantes === 0)
                    @error('email') <p class="mt-2 text-sm font-medium text-red-600">{{ $message }}</p> @enderror
                @enderror
            </div>

            <div>
                <label for="password" class="mb-1.5 block text-sm font-medium text-slate-700">Contraseña</label>
                <input
                    id="password"
                    type="password"
                    wire:model="password"
                    placeholder="••••••••"
                    autocomplete="current-password"
                    @disabled($segundosRestantes > 0)
                    class="block w-full rounded-xl border border-slate-300 bg-white px-4 py-3 text-slate-900 shadow-sm outline-none transition placeholder:text-slate-400 focus:border-blue-500 focus:ring-4 focus:ring-blue-500/15 disabled:cursor-not-allowed disabled:bg-slate-100 disabled:text-slate-500"
                >
            </div>

            <button
                type="submit"
                wire:loading.attr="disabled"
                wire:target="ingresar"
                @disabled($segundosRestantes > 0)
                class="flex w-full items-center justify-center gap-2 rounded-xl bg-blue-600 px-4 py-3 font-semibold text-white shadow-lg shadow-blue-600/20 transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-500/30 disabled:cursor-not-allowed disabled:bg-slate-300 disabled:shadow-none"
            >
                @if ($segundosRestantes > 0)
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <circle cx="12" cy="12" r="9" />
                        <path stroke-linecap="round" d="M12 7v5l3 2" />
                    </svg>
                    <span>Reintentar en {{ $segundosRestantes }} s</span>
                @else
                    <span>Iniciar sesión</span>
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14m-6-6 6 6-6 6" />
                    </svg>
                @endif
            </button>
        </form>
    </div>

    <p class="mt-6 text-center text-xs text-slate-400">Acceso seguro al sistema de almacén</p>
</div>
