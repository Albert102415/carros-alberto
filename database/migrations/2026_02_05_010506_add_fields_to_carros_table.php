<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('carros', function (Blueprint $table) {
            $table->decimal('precio_compra', 10, 2)->after('precio');
            $table->decimal('precio_venta', 10, 2)->nullable()->after('precio_compra');
            $table->enum('estado', ['disponible', 'apartado', 'vendido'])
                  ->default('disponible')
                  ->after('precio_venta');
            $table->date('fecha_venta')->nullable()->after('estado');
        });
    }

    public function down(): void
    {
        Schema::table('carros', function (Blueprint $table) {
            $table->dropColumn([
                'precio_compra',
                'precio_venta',
                'estado',
                'fecha_venta',
            ]);
        });
    }
};
