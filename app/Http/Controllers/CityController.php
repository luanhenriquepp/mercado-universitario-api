<?php

namespace App\Http\Controllers;

use App\City;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;


class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return LengthAwarePaginator
     */
    public function index()
    {
        return City::with('state')->paginate();
    }

    /**
     * @param $id
     * @return LengthAwarePaginator
     */
    public function getCitiesByUf($id)
    {
        return City::with('state')
            ->where('cd_state', '=', $id)
            ->paginate();
    }
}
