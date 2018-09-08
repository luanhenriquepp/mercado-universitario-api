<?php

use App\Profile;
use Illuminate\Database\Seeder;

class ProfileTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $profile = new Profile();
        $profile->cd_profile = Profile::ADMIN;
        $profile->ds_profile =  'Perfil de administrador';
        $profile->save();

        $profile = new Profile();
        $profile->cd_profile = Profile::STUDENT;
        $profile->ds_profile = 'Perfil de estudante';
        $profile->save();
    }
}
