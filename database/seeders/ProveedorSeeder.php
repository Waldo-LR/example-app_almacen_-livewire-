<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Proveedor;

class ProveedorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $proveedores = [
            [
                'codigo' => 'PROV-001',
                'nombre' => 'Distribuidora Andina S.A.C.',
                'ruc' => '20123456789',
                'contacto' => 'Carlos Mendoza',
                'telefono' => '+51 1 234-5678',
                'email' => 'ventas@distribuidoraandina.com',
                'direccion' => 'Av. Industrial 123, San Luis, Lima',
                'observaciones' => 'Proveedor principal de herramientas manuales. Entrega en 48h.',
                'activo' => true,
            ],
            [
                'codigo' => 'PROV-002',
                'nombre' => 'Ferretería El Tornillo E.I.R.L.',
                'ruc' => '20567891234',
                'contacto' => 'María González',
                'telefono' => '+51 1 555-0123',
                'email' => 'compras@eltornillo.pe',
                'direccion' => 'Jr. Los Artesanos 456, La Victoria, Lima',
                'observaciones' => 'Especialista en tornillería y fijaciones. Precios por volumen.',
                'activo' => true,
            ],
            [
                'codigo' => 'PROV-003',
                'nombre' => 'Importaciones Tekno S.A.',
                'ruc' => '20987654321',
                'contacto' => 'Roberto Silva',
                'telefono' => '+51 1 789-4561',
                'email' => 'roberto.silva@importtekno.com',
                'direccion' => 'Calle 15 Mz. B Lt. 8, Parque Industrial, Callao',
                'observaciones' => 'Importador directo de herramientas eléctricas Bosch, Makita, DeWalt. Garantía oficial.',
                'activo' => true,
            ],
            [
                'codigo' => 'PROV-004',
                'nombre' => 'Comercializadora Herramientas del Sur',
                'ruc' => '20345678912',
                'contacto' => 'Ana Torres',
                'telefono' => '+51 54 321-987',
                'email' => 'ana.torres@herramientasdelsur.pe',
                'direccion' => 'Av. Ejército 789, Cercado, Arequipa',
                'observaciones' => 'Cobertura sur del país. Despacho a provincias. Pago a 30 días.',
                'activo' => true,
            ],
            [
                'codigo' => 'PROV-005',
                'nombre' => 'Suministros Industriales Norte S.A.C.',
                'ruc' => '20789123456',
                'contacto' => 'Jorge Ramírez',
                'telefono' => '+51 44 654-321',
                'email' => 'jorge.ramirez@sinorte.com',
                'direccion' => 'Av. Larco 321, Urb. Primavera, Trujillo',
                'observaciones' => 'Especialista en equipos de seguridad industrial y EPP. Certificados ISO.',
                'activo' => true,
            ],
            [
                'codigo' => 'PROV-006',
                'nombre' => 'Casa del Pintor S.R.L.',
                'ruc' => '20456789123',
                'contacto' => 'Lucía Fernández',
                'telefono' => '+51 1 444-7788',
                'email' => 'lucia@casadelpintor.pe',
                'direccion' => 'Jr. Unión 234, Centro de Lima, Lima',
                'observaciones' => 'Pinturas, brochas, rodillos, cintas de enmascarar. Distribuidor autorizado Comex.',
                'activo' => true,
            ],
            [
                'codigo' => 'PROV-007',
                'nombre' => 'TecnoElectric S.A.',
                'ruc' => '20891234567',
                'contacto' => 'Pedro Castillo',
                'telefono' => '+51 1 333-2211',
                'email' => 'pedro.castillo@tecnoelectric.pe',
                'direccion' => 'Av. Javier Prado Este 1200, San Isidro, Lima',
                'observaciones' => 'Material eléctrico: cables, tableros, interruptores, conduit. Stock permanente.',
                'activo' => true,
            ],
            [
                'codigo' => 'PROV-008',
                'nombre' => 'Ferretería Los Andes',
                'ruc' => '20678912345',
                'contacto' => 'Sofía Quispe',
                'telefono' => '+51 84 222-3344',
                'email' => 'sofia.quispe@ferreterialosandes.pe',
                'direccion' => 'Av. La Cultura 567, Cusco',
                'observaciones' => 'Atención zona sur-oriente. Herramientas de corte y medición. Envíos aéreos.',
                'activo' => false,
            ],
            [
                'codigo' => 'PROV-009',
                'nombre' => 'MegaStock Ferretero',
                'ruc' => '20234567891',
                'contacto' => 'Miguel Ángel Vargas',
                'telefono' => '+51 1 999-8877',
                'email' => 'miguel.vargas@megastock.pe',
                'direccion' => 'Panamericana Sur Km 25, Lurín, Lima',
                'observaciones' => 'Mayorista general. Precios competitivos. Mínimo de pedido S/ 500.',
                'activo' => true,
            ],
            [
                'codigo' => 'PROV-010',
                'nombre' => 'Hidráulica y Neumática del Perú',
                'ruc' => '20112233445',
                'contacto' => 'Elena Rojas',
                'telefono' => '+51 1 666-5544',
                'email' => 'elena.rojas@hidraulicaperu.com',
                'direccion' => 'Av. El Derby 250, Surco, Lima',
                'observaciones' => 'Especialistas en mangueras, racores, cilindros neumáticos, válvulas. Soporte técnico.',
                'activo' => true,
            ],
        ];

        foreach ($proveedores as $proveedor) {
            Proveedor::create($proveedor);
        }
    }
}
