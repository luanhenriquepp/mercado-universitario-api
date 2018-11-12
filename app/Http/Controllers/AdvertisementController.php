<?php

namespace App\Http\Controllers;

use App\Advertisement;
use App\AdvertisementFile;
use App\AdvertisementStatus;
use App\Category;
use App\Profile;
use Faker\Provider\Image;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use JWTAuth;


class AdvertisementController extends Controller
{

    /**
     * Retora todos anúncios
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function index()
    {

        $this->validateUser();
        $advertisements = Advertisement::with('user', 'category', 'advertisement_status')
            ->where('cd_user',auth()->user()->cd_user)
            ->paginate();
        return $advertisements;
    }

    /**
     * Pagina pública
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function publicPage()
    {
        $this->validateUser();
        $advertisements = Advertisement::with('user', 'category', 'advertisement_status','user.address','user.universities')
            ->where('cd_advertisement_status', '=', AdvertisementStatus::APPROVED)
            ->paginate();
        return $advertisements;
    }

    /**
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateStatus($id , Request $request)
    {
        if (auth()->user()->cd_profile != Profile::ADMIN || $this->validateUser() == false) {
            return response()->json([
                'success' => false,
                'message' => 'Acesso negado'
            ], Response::HTTP_FORBIDDEN);
        }

        $advertisement = Advertisement::with('user', 'advertisement_status')->find($id);
        $advertisement->cd_advertisement_status = $request->input('cd_advertisement_status');

        return response()->json([
            'success' => true,
            'message' => 'Anúncio aprovado com sucesso!'
        ], Response::HTTP_OK);
    }
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $this->validateUser();
        $this->validateStoreAdvertisement($request);


        $advertisement = new Advertisement();
        $advertisement->title                   = $request->input('title');
        $advertisement->ds_advertisement        = $request->input('ds_advertisement');
        $advertisement->price                   = $request->input('price');
        $advertisement->advertisement_photo     = $request->input('advertisement_photo', null);
        $advertisement->cd_category             = $request->input('cd_category');
        $advertisement->cd_user                 = auth()->user()->cd_user;
/*        $advertisement->cd_address              = auth()->user()->cd_address;*/
        $advertisement->cd_advertisement_status = $request->input('cd_advertisement_status',
            AdvertisementStatus::AWAITINGAPPROVAL);

        if ($advertisement->save()){
            return response()->json(
                [
                    'success' => true,
                    'message' => 'Anúncio cadastrado com sucesso!'
                ], Response::HTTP_CREATED);
        }

        return response()->json([
            'success'   => false,
            'message'   => 'Ocorreu um erro no cadastro do anúncio!'
        ], Response::HTTP_BAD_REQUEST);
    }


    /**
     * @param $id
     * @return Advertisement|Advertisement[]|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|\Illuminate\Http\JsonResponse|null
     */
    public function show($id)
    {
        $this->validateUser();
        return $advertisement = Advertisement::with('user')->find($id);
    }

    /**
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function update($id, Request $request)
    {
        $this->validateUser();
        $this->validateUpdateAdvertisement($request);

        $advertisement = Advertisement::find($id);
        if ($advertisement->update($request->all())){
            return response()->json([
                'sucess'    => true,
                'message'   => 'Anúncio atualizado com sucesso!'
            ], Response::HTTP_OK);
        }

        return response()->json([
            'success'   => false,
            'message'   => 'Não foi possível atualizar o anúncio!'
        ], Response::HTTP_BAD_REQUEST);
    }

    /**
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $this->validateUser();
        $advertisement = Advertisement::find($id);

        if ($advertisement->delete()) {
            return response()->json([
                'success' => true,
                'message' => 'Anúncio excluído com sucesso!'
            ], Response::HTTP_OK);
        }

        return response()->json([
            'false' => false,
            'message'=> 'Não foi possível excluir o anúncio!'
        ], Response::HTTP_FORBIDDEN);
    }

    /**
     *  Validadores do cadastro e update de anúncio
     * @param $request
     * @return bool|\Illuminate\Http\JsonResponse
     */
    public function validateStoreAdvertisement($request)
    {
        $validator = $request->validate([
            'title'                 => 'required|max:255|min:5',
            'ds_advertisement'      => 'required|max:255|min:5',
            'price'                 => 'required',
            'cd_category'           => 'required'
        ]);

        if (!$validator) {
            return response()->json([
                    'success' => false,
                    'message' => $validator
                    ->toJson()
            ], Response::HTTP_BAD_REQUEST);
        }
        return true;
    }

    public function validateUpdateAdvertisement($request)
    {
        $validator = $request->validate([
            'title'                 => 'max:255|min:5',
            'ds_advertisement'      => 'max:255|min:5',

        ]);

        if (!$validator) {
            return response()->json([
                'success' => false,
                'message' => $validator
                    ->toJson()
            ], Response::HTTP_BAD_REQUEST);
        }
        return true;
    }
    /**
     * @return bool|\Illuminate\Http\JsonResponse
     */
    public function validateUser()
    {
        $user = auth()->user();
        if ($user == null || $user == false) {
            return response()->json([
                'success' => false,
                'message' => 'Usuário não autenticado'
            ], Response::HTTP_FORBIDDEN);
        }
        return true;
    }
}
