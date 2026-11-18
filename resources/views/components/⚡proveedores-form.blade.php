<?php
use App\Models\Proveedor;
use Livewire\Attributes\On;
use Livewire\Component;

new class extends Component
{
    public ?Proveedor $proveedor = null;
    public $codigo = "";
    public $nombre = "";
    public $ruc = "";
    public $contacto = "";
    public $telefono = "";
    public $email = "";
    public $direccion = "";
    public $observaciones = "";
    public $activo = true;
    public $showModal = false;

    protected function rules(): array
    {
        $proveedorId = $this->proveedor?->id;

        return [
            'codigo' => [
                'required',
                'max:20',
                'alpha_dash',
                \Illuminate\Validation\Rule::unique('proveedores', 'codigo')->ignore($proveedorId),
            ],
            'nombre' => ['required', 'max:150'],
            'ruc' => [
                'nullable',
                'max:20',
                \Illuminate\Validation\Rule::unique('proveedores', 'ruc')->ignore($proveedorId),
            ],
            'contacto' => ['nullable', 'max:100'],
            'telefono' => ['nullable', 'max:30'],
            'email' => ['nullable', 'email', 'max:100'],
            'direccion' => ['nullable', 'max:255'],
            'observaciones' => ['nullable', 'max:1000'],
            'activo' => ['boolean'],
        ];
    }

    protected function messages(): array
    {
        return [
            'codigo.required' => 'El código es obligatorio.',
            'codigo.max' => 'El código admite como máximo 20 caracteres.',
            'codigo.alpha_dash' => 'El código solo admite letras, números y guiones.',
            'codigo.unique' => 'Ya existe un proveedor con ese código.',
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.max' => 'El nombre admite como máximo 150 caracteres.',
            'ruc.max' => 'El RUC admite como máximo 20 caracteres.',
            'ruc.unique' => 'Ya existe un proveedor con ese RUC.',
            'contacto.max' => 'El contacto admite como máximo 100 caracteres.',
            'telefono.max' => 'El teléfono admite como máximo 30 caracteres.',
            'email.email' => 'El email debe tener un formato válido.',
            'email.max' => 'El email admite como máximo 100 caracteres.',
            'direccion.max' => 'La dirección admite como máximo 255 caracteres.',
            'observaciones.max' => 'Las observaciones admiten como máximo 1000 caracteres.',
            'activo.boolean' => 'El estado activo debe ser verdadero o falso.',
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

        if ($this->proveedor) {
            $this->proveedor->update($datos);
            $this->dispatch('swal:alert', [
                'title' => '¡Éxito!',
                'text' => 'Los datos del Proveedor fueron actualizados con éxito',
                'icon' => 'success'
            ]);
        } else {
            Proveedor::create($datos);
            $this->dispatch('swal:alert', [
                'title' => '¡Éxito!',
                'text' => 'Proveedor creado correctamente',
                'icon' => 'success'
            ]);
        }

        $this->cancelar();
        $this->dispatch('proveedor-guardado');
    }

    public function cancelar()
    {
        $this->reset(['proveedor', 'codigo', 'nombre', 'ruc', 'contacto', 'telefono', 'email', 'direccion', 'observaciones', 'activo', 'showModal']);
        $this->resetValidation();
    }

    #[On('editar-proveedor')]
    public function editar($id)
    {
        $this->proveedor = Proveedor::findOrFail($id);
        $this->fill($this->proveedor->only('codigo', 'nombre', 'ruc', 'contacto', 'telefono', 'email', 'direccion', 'observaciones', 'activo'));
        $this->resetValidation();
        $this->showModal = true;
    }

    #[On('nuevo-proveedor')]
    public function nuevo()
    {
        $this->reset(['proveedor', 'codigo', 'nombre', 'ruc', 'contacto', 'telefono', 'email', 'direccion', 'observaciones', 'activo']);
        $this->activo = true;
        $this->resetValidation();
        $this->showModal = true;
    }
};
?>
<div>
    {{-- Modal para editar/crear proveedor --}}
    @if ($showModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" wire:ignore.self>
        <div class="flex min-h-full items-center justify-center p-4">
            {{-- Overlay --}}
            <div class="fixed inset-0 bg-black/50 transition-opacity" wire:click="cancelar" aria-hidden="true"></div>

            {{-- Modal --}}
            <div class="relative w-full max-w-2xl bg-white rounded-lg shadow-xl transform transition-all">
                <div class="flex items-center justify-between p-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">
                        {{ $proveedor ? 'Editar Proveedor' : 'Nuevo Proveedor' }}
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
                    {{-- Primera fila: Código y Nombre --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {{-- Campo Código --}}
                        <div>
                            <label for="codigo" class="block text-sm font-medium text-gray-700 mb-1">Código <span class="text-red-500">*</span></label>
                            <input
                                id="codigo"
                                type="text"
                                wire:model.blur="codigo"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                placeholder="Ej: PROV-001"
                            >
                            @error('codigo')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Campo Nombre --}}
                        <div>
                            <label for="nombre" class="block text-sm font-medium text-gray-700 mb-1">Nombre / Razón Social <span class="text-red-500">*</span></label>
                            <input
                                id="nombre"
                                type="text"
                                wire:model.blur="nombre"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                placeholder="Nombre comercial del proveedor"
                            >
                            @error('nombre')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Segunda fila: RUC y Contacto --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {{-- Campo RUC --}}
                        <div>
                            <label for="ruc" class="block text-sm font-medium text-gray-700 mb-1">RUC / NIT</label>
                            <input
                                id="ruc"
                                type="text"
                                wire:model.blur="ruc"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                placeholder="Identificación fiscal"
                            >
                            @error('ruc')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Campo Contacto --}}
                        <div>
                            <label for="contacto" class="block text-sm font-medium text-gray-700 mb-1">Persona de Contacto</label>
                            <input
                                id="contacto"
                                type="text"
                                wire:model.blur="contacto"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                placeholder="Nombre del contacto"
                            >
                            @error('contacto')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Tercera fila: Teléfono y Email --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {{-- Campo Teléfono --}}
                        <div>
                            <label for="telefono" class="block text-sm font-medium text-gray-700 mb-1">Teléfono</label>
                            <input
                                id="telefono"
                                type="tel"
                                wire:model.blur="telefono"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                placeholder="+51 999 999 999"
                            >
                            @error('telefono')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Campo Email --}}
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input
                                id="email"
                                type="email"
                                wire:model.blur="email"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                placeholder="proveedor@ejemplo.com"
                            >
                            @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Campo Dirección --}}
                    <div>
                        <label for="direccion" class="block text-sm font-medium text-gray-700 mb-1">Dirección</label>
                        <input
                            id="direccion"
                            type="text"
                            wire:model.blur="direccion"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                            placeholder="Dirección física del proveedor"
                        >
                        @error('direccion')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Campo Observaciones --}}
                    <div>
                        <label for="observaciones" class="block text-sm font-medium text-gray-700 mb-1">Observaciones</label>
                        <textarea
                            id="observaciones"
                            rows="3"
                            wire:model.blur="observaciones"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                            placeholder="Notas adicionales..."
                        ></textarea>
                        @error('observaciones')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Estado Activo --}}
                    <div class="flex items-center gap-3">
                        <input
                            id="activo"
                            type="checkbox"
                            wire:model.blur="activo"
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-2 focus:ring-blue-500"
                        >
                        <label for="activo" class="text-sm font-medium text-gray-700 cursor-pointer">
                            Proveedor Activo
                        </label>
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
