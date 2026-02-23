<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('car_employee', function (Blueprint $table) {
            $table->id();

            $table->foreignId('employee_id')
                ->constrained()
                ->onDelete('cascade');

            $table->foreignId('carro_id')
                ->constrained('carros')
                ->onDelete('cascade');

            $table->boolean('pagado')->default(false);

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('car_employee');
    }
};