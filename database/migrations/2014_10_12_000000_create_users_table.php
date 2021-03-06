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
        Schema::create('tb_user', function (Blueprint $table) {
            $table->increments('cd_user');
            $table->string('name', '255');
            $table->string('registration',32)->unique()
                ->comments('Mátricula do usuário.');
            $table->string('cpf', '14')->unique();
            $table->longText('user_photo');
            $table->string('rg', '14')->unique();
            $table->date('birth')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('phone_number');
            $table->string('password_confirmation');

            $table->integer('cd_university')
                ->unsigned()
                ->comments('Foreign key da tabela de faculdades ->tb_university');
            $table->foreign('cd_university', 'cd_university_fk')
                ->references('cd_university')
                ->on('tb_university');
            $table->integer('cd_address')
                ->unsigned()
                ->comments('Foreign key da tabela de endereço ->tb_address');
            $table->foreign('cd_address', 'cd_address_fk')
                ->references('cd_address')
                ->on('tb_address');
            $table->integer('cd_profile')->unsigned();
            $table->foreign('cd_profile','cd_profile_fk')
                ->references('cd_profile')
                ->on('tb_profile');

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
        Schema::dropIfExists('tb_user');
    }
}
