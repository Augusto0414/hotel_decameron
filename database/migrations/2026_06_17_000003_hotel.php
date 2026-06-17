<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hoteles', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 200)->unique();
            $table->string('direccion', 255);
            $table->string('ciudad', 100);
            $table->string('nit', 20);
            $table->integer('numero_habitaciones');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hoteles');
    }
};
