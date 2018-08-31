<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $category = new \App\Category();
        $category->cd_category = 'SHO';
        $category->ds_category = 'Calçados';
        $category->save();

        $category = new \App\Category();
        $category->cd_category = 'CTS';
        $category->ds_category = 'Roupas';
        $category->save();
    }
}
