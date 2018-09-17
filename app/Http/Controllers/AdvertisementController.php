<?php

namespace App\Http\Controllers;

use App\Advertisement;
use App\AdvertisementStatus;
use App\Category;
use Illuminate\Http\Request;
use JWTAuth;


class AdvertisementController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $user = auth()->user();
        if ($user == false) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Usuário não autenticado',
                ], 400);
        }

        $advertisement = Advertisement::where('cd_user', $user->cd_user)
            ->paginate();

        return $advertisement;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = $request->validate([
            'title'             => 'required|max:255|min:5',
            'ds_advertisement'  => 'required|max:255|min:5',
            'price'             => 'required',
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
        $advertisement->title                   = $request->input('title');
        $advertisement->ds_advertisement        = $request->input('ds_advertisement');
        $advertisement->price                   = $request->input('price');
        $advertisement->cd_category             = $request->input('cd_category', Category::CLOTHES);
        $advertisement->cd_user                 = $user->cd_user;
        $advertisement->cd_advertisement_status = $request->input('cd_advertisement_status', AdvertisementStatus::AWAITINGAPPROVAL);
        $advertisement->save();
        return response()->json(
            [
                'success'   => true,
                'message'   => 'Anúncio cadastrado com sucesso',
                'data'      => $advertisement,
            ], 200);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        $user = auth()->user();

        if ($user == null || $user == false) {
            return response()->json([
                'success' => false,
                'message' => 'Usuário não autenticado'
            ], 400);
        }

        $advertisement = Advertisement::find($id);
        return $advertisement;
    }

    /**
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, Request $request)
    {
        $advertisement = Advertisement::find($id);
        $advertisement->update($request->all());

        return response()->json([
            'sucess' => true,
            'message' => 'Anúncio atualizado com sucesso'
        ], 200);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        Advertisement::find($id)->deleted();

        return response()->json([
            'success' => true,
            'message' => 'Anúncio deletado com sucesso'
        ], 201);

    }
}
