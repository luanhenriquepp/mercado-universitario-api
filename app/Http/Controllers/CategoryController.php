<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Database\Eloquent\Collection;

class CategoryController extends Controller
{

    /**
     * @return Category[]|Collection
     */
    public function index()
    {
        return Category::all();
    }

}
