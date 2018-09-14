<?php

namespace App\Http\Controllers;

use App\Advertisement;
use App\AdvertisementStatus;
use Illuminate\Http\Request;
use JWTAuth;


class AdvertisementController extends Controller
{
    /**
     * @return mixed
     */
    public function index()
    {
        $advertisement = Advertisement::all()->paginate();
        return $advertisement;
    }

    /**
     * @param Request $request
     * @return Advertisement
     */
    public function store(Request $request)
    {
        $validator = $request->validate([
            'title' => 'required|max:255|min:5',
            'ds_advertisement' => 'required|max:255|min:5',
            'price' => '',
        ]);

        if (!$validator) {
            return response()
                ->json([
                    'success' => false,
                    'message' => $validator
                        ->toJson()
                ], 400);
        }

        $user = auth()->user();
        $advertisement = new Advertisement();
        $advertisement->title = $request->input('title');
        $advertisement->ds_advertisement = $request->input('ds_advertisement');
        $advertisement->price = $request->input('price');
        $advertisement->cd_category = $request->input('cd_category');
        $advertisement->cd_user = $user->cd_user;
        $advertisement->cd_advertisement_status = $request
            ->input('cd_advertisement_status',
                AdvertisementStatus::AWAITINGAPPROVAL);
        $advertisement->save();
        return $advertisement;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function show($id)
    {
        $advertisement = Advertisement::find($id);
        return $advertisement;
    }

    /**
     * @param $id
     * @param Request $request
     */
    public function update($id, Request $request)
    {
        $advertisement = Advertisement::find($id);
        $advertisement->update($request->all());
    }

    /**
     * @param $id
     * @param Request $request
     */
    public function destroy($id, Request $request)
    {
        //
    }
}
