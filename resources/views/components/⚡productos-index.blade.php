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

    // public function with(): array
    // {
    //     // Agregamos 'categoria' para precargarla y evitar consultas N+1
    //     $consulta = Producto::with(['cantidad.almacen', 'categoria']);

    //     if ($this->buscar) {
    //         // Especificamos 'productos.nombre' por seguridad al hacer el join
    //         $consulta->where('productos.nombre', 'like', '%' . $this->buscar . '%');
    //     }
    //     if ($this->categoria_id) {
    //         $consulta->where('productos.categoria_id', $this->categoria_id);
    //     }
    //     if ($this->almacen_id) {
    //         $consulta->join('stock__almacen', 'productos.id', '=', 'stock__almacen.producto_id')
    //                  ->where('stock__almacen.almacen_id', $this->almacen_id)
    //                  ->select('productos.*');
    //     }

    //     return [
    //         // 'productos' => $consulta->orderBy('productos.nombre')->paginate(10),
    //         'productos' => $consulta->orderBy('productos.nombre')->get(),
    //         'categorias' => Categoria::orderBy('nombre')->get(),
    //         'almacenes'  => Almacen::orderBy('nombre')->get(),
    //     ];
    // }

    //PRUEBA TEST DE RESPUESTA 2OK REGISTROS (TIEMPO ms/TAMAÑO KB) ---> SIN PAGINACIÓN
    public function with(): array
    {
        // Aumentamos memoria y tiempo temporalmente para soportar la prueba de 20,000 filas sin paginar
        ini_set('memory_limit', '1024M');
        set_time_limit(300);

        $consulta = Producto::select('id', 'nombre', 'categoria_id')
            ->with([
                'categoria:id,nombre',
                'cantidad:id,producto_id,almacen_id,stock',
                'cantidad.almacen:id,nombre',
            ]);

        // ... resto de tus if ($this->buscar), etc.

        return [
            'productos' => $consulta->orderBy('productos.nombre')->get(),
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
    <div class="mb-4">
        <input type="text" wire:model.live.debounce.400ms="buscar" placeholder="Buscar por nombre o código" class="bg-white border border-gray-300 rounded py-2 px-4 w-full mt-3">
    </div>

    <div class="overflow-x-auto bg-white rounded-lg shadow mb-4">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-100 border-b border-gray-300 text-xs uppercase text-gray-700">
                    <th class="py-3 px-4">Producto</th>
                    <th class="py-3 px-4 text-center">Stock</th>
                    <th class="py-3 px-4">Categoría</th>
                    <th class="py-3 px-4">Almacén</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 text-sm text-gray-800">
                @forelse ($productos as $producto)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="py-3 px-4 font-medium">{{ $producto->nombre }}</td>
                        <td class="py-3 px-4 text-center">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full {{ ($producto->cantidad->first()?->stock ?? 0) > 10 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $producto->cantidad->first()?->stock ?? 0 }}
                            </span>
                        </td>
                        <td class="py-3 px-4">{{ $producto->categoria?->nombre ?? 'Sin categoría' }}</td>
                        <td class="py-3 px-4">{{ $producto->cantidad->first()?->almacen?->nombre ?? 'Sin almacén' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="py-6 px-4 text-center text-gray-500">Sin datos disponibles</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{-- PONER A COMENTARIO PARA TEST DE RESPUESTA SIN PAGINACIÓN --}}
    {{-- {{ $productos->links() }} --}}
</div>