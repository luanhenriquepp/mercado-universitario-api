<?php

namespace App\Http\Controllers;

use App\Category;

class CategoryController extends Controller
{

    /**
     * @return Category[]|\Illuminate\Database\Eloquent\Collection
     */
    public function index()
    {
        return Category::all();
    }

}
