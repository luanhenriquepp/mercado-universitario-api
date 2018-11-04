<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_address', function (Blueprint $table) {
            $table->increments('cd_address');
            $table->string('public_place')
            ->comment('Logradouro do endereço');
            $table->integer('number')->nullable();
            $table->string('complement')->nullable();
            $table->string('neighborhood')->comment('Bairro');
            $table->string('cep');
            $table->integer('cd_city')
                ->unsigned()
            ->comment('Foreign key da tabela de cidades -> tb_city');
            $table->foreign('cd_city','cd_city_fk')
                ->references('cd_city')
                ->on('tb_city');
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
        Schema::dropIfExists('tb_address');
    }
}
