<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_user_profile', function (Blueprint $table) {
            $table->increments('cd_user_profile');
            $table->integer('cd_profile')->unsigned()
                ->comments('Foreign key da tabela de perfil ->tb_profile');
            $table->foreign('cd_profile','cd_profile_fk')
                ->references('cd_profile')
                ->on('tb_profile');
            $table->integer('cd_user')->unsigned()
                ->comments('Foreign key da tabela de usuario ->tb_user');
            $table->foreign('cd_user','cd_user_profile_fk')
                ->references('cd_user')
                ->on('tb_user');
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
        Schema::dropIfExists('tb_user_profile');
    }
}
