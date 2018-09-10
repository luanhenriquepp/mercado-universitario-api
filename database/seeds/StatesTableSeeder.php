<?php

use Illuminate\Database\Seeder;
class StatesTableSeeder extends Seeder
{
    /**
     * Alimenta uma schema de tb_state
     * @author Luan Henrique
     * @return vocd_state
     */
    public function run()
    {
        DB::table('tb_state')->delete();
        DB::table('tb_state')->insert(['cd_state' => 1, 'state_name' => 'Acre', 'initials' => 'AC']);
        DB::table('tb_state')->insert(['cd_state' => 2, 'state_name' => 'Alagoas', 'initials' => 'AL']);
        DB::table('tb_state')->insert(['cd_state' => 3, 'state_name' => 'Amapá', 'initials' => 'AP']);
        DB::table('tb_state')->insert(['cd_state' => 4, 'state_name' => 'Amazonas', 'initials' => 'AM']);
        DB::table('tb_state')->insert(['cd_state' => 5, 'state_name' => 'Bahia', 'initials' => 'BA']);
        DB::table('tb_state')->insert(['cd_state' => 6, 'state_name' => 'Ceará', 'initials' => 'CE']);
        DB::table('tb_state')->insert(['cd_state' => 7, 'state_name' => 'Distrito Federal', 'initials' => 'DF']);
        DB::table('tb_state')->insert(['cd_state' => 8, 'state_name' => 'Espírito Santo', 'initials' => 'ES']);
        DB::table('tb_state')->insert(['cd_state' => 9, 'state_name' => 'Goiás', 'initials' => 'GO']);
        DB::table('tb_state')->insert(['cd_state' => 10, 'state_name' => 'Maranhão', 'initials' => 'MA']);
        DB::table('tb_state')->insert(['cd_state' => 11, 'state_name' => 'Mato Grosso', 'initials' => 'MT']);
        DB::table('tb_state')->insert(['cd_state' => 12, 'state_name' => 'Mato Grosso do Sul', 'initials' => 'MS']);
        DB::table('tb_state')->insert(['cd_state' => 13, 'state_name' => 'Minas Gerais', 'initials' => 'MG']);
        DB::table('tb_state')->insert(['cd_state' => 14, 'state_name' => 'Pará', 'initials' => 'PA']);
        DB::table('tb_state')->insert(['cd_state' => 15, 'state_name' => 'Paraíba', 'initials' => 'PB']);
        DB::table('tb_state')->insert(['cd_state' => 16, 'state_name' => 'Paraná', 'initials' => 'PR']);
        DB::table('tb_state')->insert(['cd_state' => 17, 'state_name' => 'Pernambuco', 'initials' => 'PE']);
        DB::table('tb_state')->insert(['cd_state' => 18, 'state_name' => 'Piauí', 'initials' => 'PI']);
        DB::table('tb_state')->insert(['cd_state' => 19, 'state_name' => 'Rio de Janeiro', 'initials' => 'RJ']);
        DB::table('tb_state')->insert(['cd_state' => 20, 'state_name' => 'Rio Grande do Norte', 'initials' => 'RN']);
        DB::table('tb_state')->insert(['cd_state' => 21, 'state_name' => 'Rio Grande do Sul', 'initials' => 'RS']);
        DB::table('tb_state')->insert(['cd_state' => 22, 'state_name' => 'Rondônia', 'initials' => 'RO']);
        DB::table('tb_state')->insert(['cd_state' => 23, 'state_name' => 'Roraima', 'initials' => 'RR']);
        DB::table('tb_state')->insert(['cd_state' => 24, 'state_name' => 'Santa Catarina', 'initials' => 'SC']);
        DB::table('tb_state')->insert(['cd_state' => 25, 'state_name' => 'São Paulo', 'initials' => 'SP']);
        DB::table('tb_state')->insert(['cd_state' => 26, 'state_name' => 'Sergipe', 'initials' => 'SE']);
        DB::table('tb_state')->insert(['cd_state' => 27, 'state_name' => 'Tocantins', 'initials' => 'TO']);
    }
    
}
