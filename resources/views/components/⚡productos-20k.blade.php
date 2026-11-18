<?php
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Almacen;
use Livewire\Component;
use Livewire\WithPagination;

new class extends Component
{
    use WithPagination; //---> TRAIT
    public $buscar = '';
    public $categoria_id = '';
    public $almacen_id = '';

    //PRUEBA TEST DE RESPUESTA 2OK REGISTROS (TIEMPO ms/TAMAÑO KB) ---> SIN PAGINACIÓN
    public function with(): array
    {
        // Aumentamos memoria y tiempo temporalmente para soportar la prueba de 20,000 filas sin paginar
        ini_set('memory_limit', '1024M');
        set_time_limit(300);

        $consulta = Producto::select('id', 'codigo', 'nombre', 'precio', 'categoria_id')
            ->with([
                'categoria:id,nombre',
                'cantidad:id,producto_id,almacen_id,stock',
                'cantidad.almacen:id,nombre',
            ]);

        // 1. Búsqueda general: nombre, código
        if ($this->buscar) {
            $termino = '%' . $this->buscar . '%';
            $consulta->where(function ($query) use ($termino) {
                $query->where('productos.nombre', 'like', $termino)
                      ->orWhere('productos.codigo', 'like', $termino);
            });
        }

        // 2. Filtro por Categoría
        if ($this->categoria_id) {
            $consulta->where('productos.categoria_id', $this->categoria_id);
        }

        // 3. Filtro por Almacén
        if ($this->almacen_id) {
            $consulta->whereHas('cantidad', function ($q) {
                $q->where('almacen_id', $this->almacen_id);
            });
        }

        return [
            'productos' => $consulta->orderBy('productos.nombre')->get(),
            'categorias' => Categoria::orderBy('nombre')->get(),
            'almacenes'  => Almacen::orderBy('nombre')->get(),
        ];
    }

    //************************************************************************************
    // Volver a la primera página cada vez que cambie $buscar, $categoria_id o $almacen_id
    public function updated($property)
    {
        if (in_array($property, ['buscar', 'categoria_id', 'almacen_id'])) {
            $this->resetPage();
        }
    }
};
?>

<div>
    {{-- Header con buscador y botón nuevo --}}
    <div class="mb-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex-1">
            <input type="text" wire:model.live.debounce.400ms="buscar" placeholder="Buscar por nombre o código" class="bg-white border border-gray-300 rounded py-2 px-4 w-full">
        </div>
        <div class="flex gap-2">
            <button 
                wire:click="$dispatch('nuevo-producto')"
                type="button"
                class="bg-green-600 hover:bg-green-700 text-white font-semibold px-4 py-2 rounded-lg transition-colors shadow-sm flex items-center gap-2"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Nuevo Producto
            </button>
        </div>
    </div>

    {{-- Filtros adicionales --}}
    <div class="mb-4 flex flex-wrap gap-4">
        <div>
            <select 
                wire:model.live="categoria_id" 
                class="bg-white border border-gray-300 rounded py-2 px-4 text-sm focus:outline-none focus:border-blue-500"
            >
                <option value="">Todas las categorías</option>
                @foreach ($categorias as $categoria)
                    <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <select 
                wire:model.live="almacen_id" 
                class="bg-white border border-gray-300 rounded py-2 px-4 text-sm focus:outline-none focus:border-blue-500"
            >
                <option value="">Todos los almacenes</option>
                @foreach ($almacenes as $almacen)
                    <option value="{{ $almacen->id }}">{{ $almacen->nombre }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="overflow-x-auto bg-white rounded-lg shadow mb-4">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-100 border-b border-gray-300 text-xs uppercase text-gray-700">
                    <th class="py-3 px-4">Código</th>
                    <th class="py-3 px-4">Producto</th>
                    <th class="py-3 px-4">Precio</th>
                    <th class="py-3 px-4 text-center">Stock</th>
                    <th class="py-3 px-4">Categoría</th>
                    <th class="py-3 px-4">Almacén</th>
                    <th class="py-3 px-4 text-center">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 text-sm text-gray-800">
                @forelse ($productos as $producto)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="py-3 px-4 text-gray-500 font-mono text-xs">{{ $producto->codigo ?? 'S/C' }}</td>
                        <td class="py-3 px-4 font-medium">{{ $producto->nombre }}</td>
                        <td class="py-3 px-4">${{ number_format($producto->precio, 2) }}</td>
                        <td class="py-3 px-4 text-center">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ ($producto->cantidad->first()?->stock ?? 0) > 10 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $producto->cantidad->first()?->stock ?? 0 }}
                            </span>
                        </td>
                        <td class="py-3 px-4">{{ $producto->categoria?->nombre ?? 'Sin categoría' }}</td>
                        <td class="py-3 px-4">{{ $producto->cantidad->first()?->almacen?->nombre ?? 'Sin almacén' }}</td>
                        <td class="py-3 px-4 text-center">
                            <button
                                wire:click="$dispatch('editar-producto', { id: {{ $producto->id }} })"
                                type="button"
                                class="bg-blue-500 hover:bg-blue-600 text-white text-xs font-semibold px-3 py-1.5 rounded transition-colors"
                            >
                                Editar
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-6 px-4 text-center text-gray-500">Sin datos disponibles</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{-- PONER A COMENTARIO PARA TEST DE RESPUESTA SIN PAGINACIÓN --}}
    {{-- {{ $productos->links() }} --}}
</div>