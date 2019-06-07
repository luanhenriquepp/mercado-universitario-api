<?php

namespace App\Http\Controllers;

use App\Advertisement;
use App\AdvertisementStatus;
use App\Http\Requests\RequestAdvertisement;
use App\Profile;
use App\Service\AdvertisementService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use JWTAuth;


class AdvertisementController extends Controller
{

    protected $service;

    /**
     * AdvertisementController constructor.
     * @param AdvertisementService $service
     */
    public function __construct(AdvertisementService $service)
    {
        $this->service = $service;
    }


    /**
     * @return LengthAwarePaginator
     */
    public function index()
    {
       return $this->service->showAll();
    }

    /**
     * Pagina pública
     * @return LengthAwarePaginator
     */
    public function publicPage()
    {
        $advertisements = Advertisement::with('user', 'category', 'advertisement_status','user.address','user.universities')
            ->where('cd_advertisement_status', '=', AdvertisementStatus::APPROVED)
            ->paginate();
        return $advertisements;
    }


    /**
     * @param $id
     * @return Advertisement|Advertisement[]|Builder|Builder[]|Collection|Model|null
     */
    public function showPending($id)
    {
        return $advertisement = Advertisement::with('user','category')->find($id);
    }
    /**
     * @return LengthAwarePaginator
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
     * @param RequestAdvertisement $request
     * @return JsonResponse
     */
    public function updateAdvertisementStatus($id , RequestAdvertisement $request)
    {
         return $this->service->updateStatusAdvertisement($id, $request);
    }

    /**
     * @param RequestAdvertisement $request
     * @return JsonResponse
     */
    public function store(RequestAdvertisement $request)
    {
       return $this->service->createAdvertisement($request);

    }


    /**
     * @param $id
     * @return Advertisement|Advertisement[]|Builder|Builder[]|Collection|Model|JsonResponse|null
     */
    public function show($id)
    {
        return $advertisement = Advertisement::with('user','category')->find($id);
    }

    /**
     * @param $id
     * @param Request $request
     * @return JsonResponse
     */
    public function update($id, Request $request)
    {
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
     * @return JsonResponse
     */
    public function destroy($id)
    {
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
}
