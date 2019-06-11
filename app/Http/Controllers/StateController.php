<?php

namespace App\Http\Controllers;

use App\State;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class StateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return State[]|Collection
     */
    public function index()
    {
        return State::all();
    }

}
