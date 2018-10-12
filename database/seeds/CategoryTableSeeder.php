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
        $category               = new Category();
        $category->cd_category  = Category::FASHION;
        $category->ds_category  = 'Moda e beleza';
        $category->save();

        $category               = new Category();
        $category->cd_category  = Category::ELECTRONICS;
        $category->ds_category  = 'Eletrônicos e celulares';
        $category->save();

        $category = new Category();
        $category->cd_category = Category::DOMESTIC;
        $category->ds_category = 'Utensílios domésticos';
        $category->save();

        $category              = new Category();
        $category->cd_category = Category::SERVICES;
        $category->ds_category = 'Serviços';
        $category->save();

        $category               = new Category();
        $category->cd_category  = Category::MUSIC;
        $category->ds_category  = 'Música';
        $category->save();

        $category = new Category();
        $category->cd_category = Category::CHILDREN;
        $category->ds_category = 'Artigos infantis';
        $category->save();

        $category               = new Category();
        $category->cd_category  = Category::SPORTS;
        $category->ds_category  = 'Esportes e lazer';
        $category->save();

        $category               = new Category();
        $category->cd_category  = Category::ANIMALS;
        $category->ds_category  = 'Animais de estimação';
        $category->save();

        $category               = new Category();
        $category->cd_category  = Category::BOOKS;
        $category->ds_category  = 'Livros';
        $category->save();
    }
}
