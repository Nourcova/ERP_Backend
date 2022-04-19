<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_kpi', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('rate');
            // ->default(1);

            // $table->foreignId('employee_id')->constrained();
            // $table->foreignId('kpi_id')->constrained();
            

            $table->integer('employee_id')->unsigned();
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');

            $table->integer('kpi_id')->unsigned();
            $table->foreign('kpi_id')->references('id')->on('kpis')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('employee_kpi');
    }
};
