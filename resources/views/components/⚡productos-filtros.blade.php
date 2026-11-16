<?php
use App\Models\Producto;
use App\Models\Categoria;
use App\Models\Almacen;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;

new class extends Component
{
    use WithPagination;

    #[Url(except: '')]
    public $buscar = '';

    #[Url(except: '')]
    public $categoria_id = '';

    #[Url(except: '')]
    public $almacen_id = '';

    // Filtro propio: Estado del stock ('bajo', 'disponible', 'agotado')
    #[Url(except: '')]
    public $estado_stock = '';

    public function with(): array
    {
        $consulta = Producto::with([
            'categoria',
            'cantidad' => function ($query) {
                if ($this->almacen_id) {
                    $query->where('almacen_id', $this->almacen_id);
                }
                $query->with('almacen');
            }
        ]);

        // 1. Búsqueda general: nombre, código, categoría o almacén
        if ($this->buscar) {
            $termino = '%' . $this->buscar . '%';

            $consulta->where(function ($query) use ($termino) {
                $query->where('productos.nombre', 'like', $termino)
                      ->orWhere('productos.codigo', 'like', $termino)
                      ->orWhereHas('categoria', function ($q) use ($termino) {
                          $q->where('nombre', 'like', $termino);
                      })
                      ->orWhereHas('cantidad.almacen', function ($q) use ($termino) {
                          $q->where('nombre', 'like', $termino);
                      });
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

        // 4. Filtro propio: Nivel de Stock (considerando el almacén si está seleccionado)
        if ($this->estado_stock) {
            $consulta->whereHas('cantidad', function ($q) {
                if ($this->almacen_id) {
                    $q->where('almacen_id', $this->almacen_id);
                }

                if ($this->estado_stock === 'bajo') {
                    $q->where('stock', '>', 0)->where('stock', '<=', 10);
                } elseif ($this->estado_stock === 'disponible') {
                    $q->where('stock', '>', 10);
                } elseif ($this->estado_stock === 'agotado') {
                    $q->where('stock', '<=', 0);
                }
            });
        }

        return [
            'productos'  => $consulta->orderBy('productos.nombre')->paginate(10),
            'categorias' => Categoria::orderBy('nombre')->get(),
            'almacenes'  => Almacen::orderBy('nombre')->get(),
        ];
    }

    // Volver a la página 1 al cambiar cualquier filtro
    public function updated($property)
    {
        if (in_array($property, ['buscar', 'categoria_id', 'almacen_id', 'estado_stock'])) {
            $this->resetPage();
        }
    }

    // Botón opcional para limpiar todos los filtros y la URL
    public function limpiarFiltros()
    {
        $this->reset(['buscar', 'categoria_id', 'almacen_id', 'estado_stock']);
        $this->resetPage();
    }
};
?>

<div>
    {{-- Barra de búsqueda y 3 filtros en fila --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4 mt-3">
        <div>
            <input 
                type="text" 
                wire:model.live.debounce.400ms="buscar" 
                placeholder="Buscar producto, código, categoría o almacén..." 
                class="bg-white border border-gray-300 rounded py-2 px-4 w-full text-sm focus:outline-none focus:border-blue-500"
            >
        </div>

        <div>
            <select 
                wire:model.live="categoria_id" 
                class="bg-white border border-gray-300 rounded py-2 px-4 w-full text-sm focus:outline-none focus:border-blue-500"
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
                class="bg-white border border-gray-300 rounded py-2 px-4 w-full text-sm focus:outline-none focus:border-blue-500"
            >
                <option value="">Todos los almacenes</option>
                @foreach ($almacenes as $almacen)
                    <option value="{{ $almacen->id }}">{{ $almacen->nombre }}</option>
                @endforeach
            </select>
        </div>

        {{-- Selector del filtro propio: Nivel de Stock --}}
        <div class="flex gap-2">
            <select 
                wire:model.live="estado_stock" 
                class="bg-white border border-gray-300 rounded py-2 px-4 w-full text-sm focus:outline-none focus:border-blue-500"
            >
                <option value="">Todos los niveles de stock</option>
                <option value="disponible">Stock disponible (> 10)</option>
                <option value="bajo">Stock bajo (1 - 10)</option>
                <option value="agotado">Agotado (0)</option>
            </select>

            @if ($buscar || $categoria_id || $almacen_id || $estado_stock)
                <button 
                    wire:click="limpiarFiltros" 
                    type="button"
                    title="Limpiar filtros"
                    class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold px-3 py-2 rounded text-sm transition-colors"
                >
                    ✕
                </button>
            @endif
        </div>
    </div>

    {{-- Tabla de resultados --}}
    <div class="overflow-x-auto bg-white rounded-lg shadow mb-4">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-100 border-b border-gray-300 text-xs uppercase text-gray-700">
                    <th class="py-3 px-4">Código</th>
                    <th class="py-3 px-4">Producto</th>
                    <th class="py-3 px-4 text-center">Stock</th>
                    <th class="py-3 px-4">Categoría</th>
                    <th class="py-3 px-4">Almacén</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 text-sm text-gray-800">
                @forelse ($productos as $producto)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="py-3 px-4 text-gray-500 font-mono text-xs">{{ $producto->codigo ?? 'S/C' }}</td>
                        <td class="py-3 px-4 font-medium">{{ $producto->nombre }}</td>
                        <td class="py-3 px-4 text-center">
                            @php $stockActual = $producto->cantidad->first()?->stock ?? 0; @endphp
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $stockActual > 10 ? 'bg-green-100 text-green-800' : ($stockActual > 0 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                {{ $stockActual }}
                            </span>
                        </td>
                        <td class="py-3 px-4">{{ $producto->categoria?->nombre ?? 'Sin categoría' }}</td>
                        <td class="py-3 px-4">{{ $producto->cantidad->first()?->almacen?->nombre ?? 'Sin almacén' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-6 px-4 text-center text-gray-500">Sin datos disponibles</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Paginación --}}
    {{ $productos->links() }}
</div>