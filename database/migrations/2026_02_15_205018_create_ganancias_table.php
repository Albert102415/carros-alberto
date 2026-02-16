<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('ganancias', function (Blueprint $table) {
            $table->id();

            $table->foreignId('carros_id')
                ->constrained('carros')
                ->onDelete('cascade');

            $table->decimal('total_invertido', 12, 2);
            $table->decimal('precio_venta', 12, 2);
            $table->decimal('ganancia', 12, 2);

            $table->date('fecha_venta');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('ganancias');
    }

};
