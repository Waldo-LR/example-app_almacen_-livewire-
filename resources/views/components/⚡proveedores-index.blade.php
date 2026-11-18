<?php
use App\Models\Proveedor;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use Livewire\Attributes\On;

new class extends Component
{
    use WithPagination;

    #[Url(except: '')]
    public $buscar = '';

    #[Url(except: '')]
    public $estado = '';

    public function with(): array
    {
        $consulta = Proveedor::query();

        if ($this->buscar) {
            $termino = '%' . $this->buscar . '%';

            $consulta->where(function ($query) use ($termino) {
                $query->where('nombre', 'like', $termino)
                      ->orWhere('codigo', 'like', $termino)
                      ->orWhere('ruc', 'like', $termino)
                      ->orWhere('contacto', 'like', $termino)
                      ->orWhere('email', 'like', $termino);
            });
        }

        if ($this->estado) {
            $consulta->where('activo', $this->estado === 'activo');
        }

        return [
            'proveedores' => $consulta->orderBy('nombre')->paginate(10),
        ];
    }

    public function updated($property)
    {
        if (in_array($property, ['buscar', 'estado'])) {
            $this->resetPage();
        }
    }

    public function limpiarFiltros()
    {
        $this->reset(['buscar', 'estado']);
        $this->resetPage();
    }
};
?>

<div>
    {{-- Header con buscador y botón nuevo --}}
    <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex-1">
            <input 
                type="text" 
                wire:model.live.debounce.400ms="buscar" 
                placeholder="Buscar por nombre, código, RUC, contacto o email..." 
                class="bg-white border border-gray-300 rounded py-2 px-4 w-full text-sm focus:outline-none focus:border-blue-500"
            >
        </div>
        <div class="flex gap-2">
            <button 
                wire:click="$dispatch('nuevo-proveedor')"
                type="button"
                class="bg-green-600 hover:bg-green-700 text-white font-semibold px-4 py-2 rounded-lg transition-colors shadow-sm flex items-center gap-2"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Nuevo Proveedor
            </button>
        </div>
    </div>

    {{-- Filtros adicionales --}}
    <div class="mb-4 flex flex-wrap gap-4">
        <div>
            <select 
                wire:model.live="estado" 
                class="bg-white border border-gray-300 rounded py-2 px-4 text-sm focus:outline-none focus:border-blue-500"
            >
                <option value="">Todos los estados</option>
                <option value="activo">Activos</option>
                <option value="inactivo">Inactivos</option>
            </select>
        </div>

        @if ($buscar || $estado)
            <button 
                wire:click="limpiarFiltros" 
                type="button"
                title="Limpiar filtros"
                class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold px-3 py-2 rounded text-sm transition-colors self-end"
            >
                ✕ Limpiar
            </button>
        @endif
    </div>

    {{-- Tabla de resultados --}}
    <div class="overflow-x-auto bg-white rounded-lg shadow mb-4">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-100 border-b border-gray-300 text-xs uppercase text-gray-700">
                    <th class="py-3 px-4">Código</th>
                    <th class="py-3 px-4">Proveedor</th>
                    <th class="py-3 px-4">RUC</th>
                    <th class="py-3 px-4">Contacto</th>
                    <th class="py-3 px-4">Teléfono</th>
                    <th class="py-3 px-4">Email</th>
                    <th class="py-3 px-4 text-center">Estado</th>
                    <th class="py-3 px-4 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 text-sm text-gray-800">
                @forelse ($proveedores as $proveedor)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="py-3 px-4 text-gray-500 font-mono text-xs">{{ $proveedor->codigo }}</td>
                        <td class="py-3 px-4 font-medium">{{ $proveedor->nombre }}</td>
                        <td class="py-3 px-4">{{ $proveedor->ruc ?? '—' }}</td>
                        <td class="py-3 px-4">{{ $proveedor->contacto ?? '—' }}</td>
                        <td class="py-3 px-4">{{ $proveedor->telefono ?? '—' }}</td>
                        <td class="py-3 px-4">{{ $proveedor->email ?? '—' }}</td>
                        <td class="py-3 px-4 text-center">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $proveedor->activo ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $proveedor->activo ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-center">
                            <button
                                wire:click="$dispatch('editar-proveedor', { id: {{ $proveedor->id }} })"
                                type="button"
                                class="bg-blue-500 hover:bg-blue-600 text-white text-xs font-semibold px-3 py-1.5 rounded transition-colors"
                            >
                                Editar
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="py-6 px-4 text-center text-gray-500">Sin datos disponibles</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Paginación --}}
    {{ $proveedores->links() }}
</div>