<?php

namespace App\Http\Controllers;

use App\Advertisement;
use App\AdvertisementStatus;
use App\Http\Requests\RequestAdvertisement;
use App\Profile;
use App\Service\AdvertisementService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use JWTAuth;


class AdvertisementController extends Controller
{

    public $service;
    public function __construct(AdvertisementService $service)
    {
        $this->service = $service;
    }

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
     * @return Advertisement|Advertisement[]|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    public function showPending($id)
    {
        $this->validateUser();
        return $advertisement = Advertisement::with('user','category')->find($id);
    }
    /**
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function awaitingApprovalAdvertisement()
    {
         $advertisement = Advertisement::with('user', 'advertisement_status','category')
            ->where('cd_advertisement_status', '=', AdvertisementStatus::AWAITINGAPPROVAL)
            ->paginate();
         return $advertisement;
    }

    /**
     * Método destinado a aprovação de anúncio, fazendo validação se o usuário é um admin e se ele está autenticado
     * Verifica se o usuário preencheu o campo de status do anúncio
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateAdvertisementStatus($id , Request $request)
    {
        if (auth()->user()->cd_profile != Profile::ADMIN || $this->validateUser() == false) {
            return response()->json([
                'success' => false,
                'message' => 'Acesso negado'
            ], Response::HTTP_FORBIDDEN);
        }

        $advertisement = Advertisement::with('user', 'advertisement_status')->find($id);
        if ($request->input('cd_advertisement_status') == false) {
            return response()->json([
                'success' => false,
                'message' => 'Por favor informe o status do anúncio!'
            ], Response::HTTP_BAD_REQUEST);
        }

        $advertisement->cd_advertisement_status = $request->input('cd_advertisement_status');

       if ($advertisement->update()) {
           return response()->json([
               'success' => true,
               'message' => 'Anúncio aprovado com sucesso!'
           ], Response::HTTP_OK);
       }
        return response()->json([
            'success' => false,
            'message' => 'Não foi possível atualizar os dados do anúncio!'
        ], Response::HTTP_BAD_REQUEST);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(RequestAdvertisement $request)
    {
       $this->validateUser();
       $data = $this->service->createAdvertisement($request);
        if ($data->save()){
            return response()->json(
                [
                    'data' => $data,
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
        return $advertisement = Advertisement::with('user','category')->find($id);
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
        $advertisement->cd_advertisement_status = $request->input('cd_advertisement_status',
            AdvertisementStatus::AWAITINGAPPROVAL);
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
