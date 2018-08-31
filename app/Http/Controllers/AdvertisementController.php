<?php

namespace App\Http\Controllers;

use App\Advertisement;
use App\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Illuminate\Support\Facades\Input;

class AdvertisementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


        $rules = [
            'title' => 'required|max:255|min:5',
            'ds_advertisement' => 'required|max:255|min:5',
            'price' => 'required',

        ];

        $user = auth()->user();
        $adversiment = new Advertisement();
        $adversiment->cd_user = $user->cd_user;
        $adversiment->title = $request->input('title');
        $adversiment->ds_advertisement = $request->input('ds_advertisement');
        $adversiment->price = $request->input('price');
        $adversiment->cd_category = Category::SHOES;

        $validator = Validator::make(Input::all(), $rules);

        if ($validator->fails()){
            return response()
                ->json($validator->errors()->toJson(), 400);
        }
        $adversiment->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Advertisement  $advertisement
     * @return \Illuminate\Http\Response
     */
    public function show(Advertisement $advertisement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Advertisement  $advertisement
     * @return \Illuminate\Http\Response
     */
    public function edit(Advertisement $advertisement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Advertisement  $advertisement
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Advertisement $advertisement)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Advertisement  $advertisement
     * @return \Illuminate\Http\Response
     */
    public function destroy(Advertisement $advertisement)
    {
        //
    }
}
