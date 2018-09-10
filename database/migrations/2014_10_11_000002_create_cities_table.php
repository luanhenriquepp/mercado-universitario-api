<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_city', function (Blueprint $table) {
            $table->increments('cd_city')
                ->comment('código da cidade');
            $table->string('city_name')
                ->comment('nome da cidade');
            $table->string('ibge_code')
                ->comment('código do ibg');
            $table->integer('cd_state')
                ->unsigned()
                ->comment('código da foreign key da tabela de estados');
            $table->foreign('cd_state','cd_state_fk')
                ->references('cd_state')
                ->on('tb_state');
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
        Schema::dropIfExists('tb_city');
    }
}
