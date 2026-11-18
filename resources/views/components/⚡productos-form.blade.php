<?php
use App\Models\Producto;
use Illuminate\Validation\Rule;
use Livewire\Attributes\On;
use Livewire\Component;
new class extends Component {
    public ?Producto $producto = null;
    public $codigo = '';
    public $nombre = '';
    public $descripcion = '';
    public $precio = '';
    public $showModal = false;

    protected function rules(): array
    {
        return [
        'codigo' => ['required', 'max:20', 'alpha_dash',
        Rule::unique('productos', 'codigo')->ignore($this->producto?->id)],
        'nombre' => ['required', 'max:100'],
        'descripcion' => ['required', 'max:500'],
        'precio' => ['required', 'numeric', 'gt:0', 'max:999999.99', 'decimal:0,2'],
        ];
    }
    protected function messages(): array
    {
        return [
        'codigo.required' => 'El código es obligatorio.',
        'codigo.max' => 'El código admite como máximo 20 caracteres.',
        'codigo.alpha_dash' => 'El código solo admite letras, números y guiones.',
        'codigo.unique' => 'Ya existe un producto con ese código.',
        'nombre.required' => 'El nombre es obligatorio.',
        'nombre.max' => 'El nombre admite como máximo 100 caracteres.',
        'descripcion.required' => 'La descripción es obligatoria.',
        'descripcion.max' => 'La descripción admite como máximo 500 caracteres.',
        'precio.required' => 'El precio es obligatorio.',
        'precio.numeric' => 'El precio debe ser un número.',
        'precio.gt' => 'El precio debe ser mayor que cero.',
        'precio.max' => 'El precio no puede superar 999999.99.',
        'precio.decimal' => 'El precio admite como máximo 2 decimales.',
        ];
    }

    public function updated($propiedad)
    {
        if ($propiedad === 'codigo') {
            $this->codigo = trim((string) $this->codigo);
        }
        $this->validateOnly($propiedad);
    }

    public function guardar()
    {
        $datos = $this->validate();
        if ($this->producto) {
            $this->producto->update($datos);
            $this->dispatch('swal:alert', [
                'title' => '¡Éxito!',
                'text' => 'Los datos del Producto fueron actualizado con éxito',
                'icon' => 'success'
            ]);
        } else {
            Producto::create($datos);
            $this->dispatch('swal:alert', [
                'title' => '¡Éxito!',
                'text' => 'Producto creado correctamente',
                'icon' => 'success'
            ]);
        }
        $this->cancelar();
        $this->dispatch('producto-guardado');
    }
    public function cancelar()
    {
        $this->reset(['producto', 'codigo', 'nombre', 'descripcion', 'precio', 'showModal']);
        $this->resetValidation();
    }

    #[On('editar-producto')]
    public function editar($id)
    {
        $this->producto = Producto::findOrFail($id);
        $this->fill($this->producto->only('codigo', 'nombre', 'descripcion', 'precio'));
        $this->resetValidation();
        $this->showModal = true;
    }
    
    #[On('nuevo-producto')]
    public function nuevo()
    {
        $this->reset(['producto', 'codigo', 'nombre', 'descripcion', 'precio']);
        $this->resetValidation();
        $this->showModal = true;
    }
}
?>
<div>
    {{-- Modal para editar/crear producto --}}
    @if ($showModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" wire:ignore.self>
        <div class="flex min-h-full items-center justify-center p-4">
            {{-- Overlay --}}
            <div class="fixed inset-0 bg-black/50 transition-opacity" wire:click="cancelar" aria-hidden="true"></div>
            
            {{-- Modal --}}
            <div class="relative w-full max-w-lg bg-white rounded-lg shadow-xl transform transition-all">
                <div class="flex items-center justify-between p-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">
                        {{ $producto ? 'Editar Producto' : 'Nuevo Producto' }}
                    </h3>
                    <button 
                        type="button" 
                        wire:click="cancelar"
                        class="text-gray-400 hover:text-gray-500 transition-colors"
                        aria-label="Cerrar modal"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                
                <form wire:submit="guardar" class="p-4 space-y-4">
                    {{-- Campo Código --}}
                    <div>
                        <label for="codigo" class="block text-sm font-medium text-gray-700 mb-1">Código</label>
                        <input 
                            id="codigo" 
                            type="text" 
                            wire:model.blur="codigo"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                            placeholder="Ej: HER-0001"
                        >
                        @error('codigo')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
{{-- Campo Nombre --}}
                    <div>
                        <label for="nombre" class="block text-sm font-medium text-gray-700 mb-1">Nombre</label>
                        <input 
                            id="nombre" 
                            type="text" 
                            wire:model.blur="nombre"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                            placeholder="Nombre del producto"
                        >
                        @error('nombre')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    {{-- Campo Descripción --}}
                    <div>
                        <label for="descripcion" class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                        <textarea 
                            id="descripcion" 
                            rows="3"
                            wire:model.blur="descripcion"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                            placeholder="Descripción del producto"
                        ></textarea>
                        @error('descripcion')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    {{-- Campo Precio --}}
                    <div>
                        <label for="precio" class="block text-sm font-medium text-gray-700 mb-1">Precio</label>
                        <input 
                            id="precio" 
                            type="number" 
                            step="0.01"
                            min="0.01"
                            wire:model.blur="precio"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                            placeholder="0.00"
                        >
                        @error('precio')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
{{-- Botones de acción --}}
                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                        <button 
                            type="button" 
                            wire:click="cancelar"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                        >
                            Cancelar
                        </button>
                        <button 
                            type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors"
                            wire:loading.attr="disabled"
                        >
                            <span wire:loading.remove>Guardar</span>
                            <span wire:loading class="flex items-center gap-2">
                                <svg class="animate-spin h-4 w-4" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                </svg>
                                Guardando...
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
</div>
