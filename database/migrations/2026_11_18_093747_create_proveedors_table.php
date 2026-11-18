<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('proveedores', function (Blueprint $table) {
            $table->id();
            $table->string('codigo', 20)->unique();           // Código único del proveedor (ej: PROV-001)
            $table->string('nombre', 150);                    // Nombre comercial / razón social
            $table->string('ruc', 20)->unique()->nullable();  // RUC / NIT / Identificación fiscal
            $table->string('contacto', 100)->nullable();      // Persona de contacto
            $table->string('telefono', 30)->nullable();       // Teléfono principal
            $table->string('email', 100)->nullable();         // Email de contacto
            $table->string('direccion', 255)->nullable();     // Dirección física
            $table->text('observaciones')->nullable();        // Notas adicionales
            $table->boolean('activo')->default(true);         // Estado activo/inactivo
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proveedores');
    }
};
