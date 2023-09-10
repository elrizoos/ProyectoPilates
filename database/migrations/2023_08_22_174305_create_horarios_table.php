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
        Schema::create('horarios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('codigoClase');
            $table->unsignedBigInteger('codigoGrupo');
            $table->unsignedBigInteger('codigoEmpleado');
            $table->string('diaSemana');
            $table->time('horaInicio');
            $table->time('horaFin');
            $table->date('primerDia');
            $table->boolean('repetir')->nullable();
            $table->smallInteger('repeticiones')->nullable();
            $table->foreign('codigoClase')->references('id')->on('clases');
            $table->foreign('codigoGrupo')->references('id')->on('grupos');
            $table->foreign('codigoEmpleado')->references('id')->on('empleados');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('horarios');
    }
};
