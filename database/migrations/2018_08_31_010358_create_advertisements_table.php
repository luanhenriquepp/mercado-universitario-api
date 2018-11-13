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
            $table->string('ds_advertisement');
            $table->double('price');
            $table->longText('advertisement_photo')->nullable();
            $table->integer('cd_user')->unsigned()
                ->comments('Foreign key da tabela de usuários ->tb_user');
            $table->foreign('cd_user','cd_user_fk')
                ->references('cd_user')
                ->on('tb_user');
            $table->integer('cd_category')->unsigned();
            $table->foreign('cd_category','cd_category_fk')
                ->references('cd_category')
                ->on('tb_category');

            $table->string('cd_advertisement_status','3')
                ->comments('Foreign key da tabela de anúncio status ->tb_advertisement_status');
            $table->foreign('cd_advertisement_status','cd_advertisement_status_fk')
                ->references('cd_advertisement_status')
                ->on('tb_advertisement_status');


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
