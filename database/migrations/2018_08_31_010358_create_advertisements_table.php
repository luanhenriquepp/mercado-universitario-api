<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdvertisementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_advertisement', function (Blueprint $table) {
            $table->increments('cd_advertisement');
            $table->string('title');
            //$table->string('status'); implementar em um outro momento tabela de status do anuncio
            $table->text('ds_advertisement');
            $table->double('price');
            $table->integer('cd_user')->unsigned();
            $table->foreign('cd_user','cd_user_fk')
                ->references('cd_user')
                ->on('tb_user');
            $table->string('cd_category');
            $table->foreign('cd_category','cd_category_fk')
                ->references('cd_category')
                ->on('tb_category');
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
        Schema::dropIfExists('tb_advertisement');
    }
}
