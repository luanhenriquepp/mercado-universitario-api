<?php

use App\Category;
use Illuminate\Database\Seeder;

class CategoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $category = new Category();
        $category->cd_category = Category::SHOES;
        $category->ds_category = 'Calçados';
        $category->save();

        $category = new Category();
        $category->cd_category = Category::BOOKS;
        $category->ds_category = 'Livros';
        $category->save();
    }
}
