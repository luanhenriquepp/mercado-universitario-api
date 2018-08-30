<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('cd_user');
            $table->string('name','255');
            $table->string('cpf', '14')->unique();
            $table->string('rg', '14')->unique();
            $table->dateTime('data_nacimento')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->integer('cd_university')
                ->unsigned();
            $table->foreign('cd_university', 'cd_university_fk')
                ->references('cd_university')
                ->on('tb_universities');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
