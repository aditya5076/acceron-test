<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id')->nullable();
            $table->string('name')->nullable();
            $table->string('title')->nullable();
            $table->string('department')->nullable();
            $table->string('business_unit')->nullable();
            $table->string('gender')->nullable();
            $table->string('ethnicity')->nullable();
            $table->integer('age')->nullable();
            $table->string('hire_date')->nullable();
            $table->string('annual_salary')->nullable();
            $table->string('bonus')->nullable();
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->string('exit_date')->nullable();
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
        Schema::dropIfExists('employees');
    }
}
