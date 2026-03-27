<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('expedientes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('carro_id')->constrained('carros')->onDelete('cascade');
            $table->string('cliente')->nullable();
            $table->string('telefono', 20)->nullable();
            $table->timestamps();
        });

        Schema::create('expediente_archivos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('expediente_id')->constrained('expedientes')->onDelete('cascade');
            $table->string('nombre');
            $table->string('archivo');
            $table->string('tipo')->default('otro');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expediente_archivos');
        Schema::dropIfExists('expedientes');
    }
};
