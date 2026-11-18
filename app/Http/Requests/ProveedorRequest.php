<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProveedorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $proveedorId = $this->route('proveedor')?->id;

        return [
            'codigo' => [
                'required',
                'max:20',
                'alpha_dash',
                Rule::unique('proveedores', 'codigo')->ignore($proveedorId),
            ],
            'nombre' => ['required', 'max:150'],
            'ruc' => [
                'nullable',
                'max:20',
                Rule::unique('proveedores', 'ruc')->ignore($proveedorId),
            ],
            'contacto' => ['nullable', 'max:100'],
            'telefono' => ['nullable', 'max:30'],
            'email' => ['nullable', 'email', 'max:100'],
            'direccion' => ['nullable', 'max:255'],
            'observaciones' => ['nullable', 'max:1000'],
            'activo' => ['boolean'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
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

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'codigo' => 'código',
            'nombre' => 'nombre',
            'ruc' => 'RUC',
            'contacto' => 'contacto',
            'telefono' => 'teléfono',
            'email' => 'email',
            'direccion' => 'dirección',
            'observaciones' => 'observaciones',
            'activo' => 'estado activo',
        ];
    }
}
