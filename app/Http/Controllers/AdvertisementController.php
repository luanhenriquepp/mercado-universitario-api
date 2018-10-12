<?php

namespace App\Http\Controllers;

use App\Advertisement;
use App\AdvertisementStatus;
use App\Category;
use Faker\Provider\Image;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use JWTAuth;


class AdvertisementController extends Controller
{
    /**
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function index()
    {
        $user = auth()->user();
        if ($user == false) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Usuário não autenticado',
                ], Response::HTTP_BAD_REQUEST);
        }

        $advertisement = Advertisement::with('user', 'category', 'advertisement_status')
            ->where('cd_user', $user->cd_user)->paginate();
        return $advertisement;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = $request->validate([
            'title'            => 'required|max:255|min:5',
            'ds_advertisement' => 'required|max:255|min:32',
            'price'            => 'required',
            'cd_category'      => 'required'
        ]);

        if (!$validator) {
            return response()
                ->json([
                    'success' => false,
                    'message' => $validator
                        ->toJson()
                ], Response::HTTP_BAD_REQUEST);
        }


        $advertisement = new Advertisement();
        $advertisement->title = $request->input('title');
        $advertisement->ds_advertisement = $request->input('ds_advertisement');
        $advertisement->price = $request->input('price');
        $advertisement->advertisement_photo = $request->input('advertisement_photo');
        $advertisement->cd_category = $request->input('cd_category');
        $advertisement->cd_user = auth()->user()->cd_user;
        $advertisement->cd_advertisement_status = $request->input('cd_advertisement_status',
            AdvertisementStatus::AWAITINGAPPROVAL);
        $advertisement->save();
        return response()->json(
            [
                'success' => true,
                'message' => 'Anúncio cadastrado com sucesso!',
                'data' => $advertisement,
            ], Response::HTTP_CREATED);
    }


    /**
     * @param $id
     * @return Advertisement|Advertisement[]|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|\Illuminate\Http\JsonResponse|null
     */
    public function show($id)
    {
        //Implementar se o anúncio pertence ao usuário autenticado
        $user = auth()->user();

        if ($user == null || $user == false) {
            return response()->json([
                'success' => false,
                'message' => 'Usuário não autenticado'
            ], Response::HTTP_BAD_REQUEST);
        }

        return $advertisement = Advertisement::with('user')->find($id);

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
        ], Response::HTTP_OK);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $advertisement = Advertisement::find($id);

        if ($advertisement->delete()) {
            return response()->json([
                'success' => true,
                'message' => 'Anúncio excluído com sucesso'
            ], Response::HTTP_OK);
        }

        return response()->json([
            'false' => false,
            'message'=> 'Não foi possível excluir o anúncio!'
        ], Response::HTTP_FORBIDDEN);


    }
}
