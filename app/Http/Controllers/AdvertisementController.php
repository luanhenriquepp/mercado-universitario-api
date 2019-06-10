<?php

namespace App\Http\Controllers;

use App\Advertisement;
use App\Http\Requests\RequestAdvertisement;
use App\Service\AdvertisementService;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;

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
       return $this->service->getAll();
    }

    /**
     * @return LengthAwarePaginator
     */
    public function publicPage()
    {
       return $this->service->publicPageAll();
    }

    /**
     * Método retorna todos os anúncios do usuários pendente do usuário.
     * @param $id
     * @return Advertisement|Advertisement[]|Builder|Builder[]|Collection|Model|null
     */
    public function showPending($id)
    {
        return $this->service->pendingAdvertisement($id);
    }

    /**
     * Método retorna todos os anúncios que estão aguardando aprovação
     * @return LengthAwarePaginator
     */
    public function awaitingApprovalAdvertisement()
    {
        return $this->service->awaitingApproval();
    }

    /**
     * Método destinado a aprovação de anúncio, fazendo validação se o usuário é um admin e se ele está autenticado
     * Verifica se o usuário preencheu o campo de status do anúncio
     * @param $id
     * @param RequestAdvertisement $request
     * @return JsonResponse
     */
    public function updateStatus($id , RequestAdvertisement $request)
    {
         return $this->service->updateAdvertisementStatus($id, $request);
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
     * @return Advertisement|Advertisement[]|Builder|Builder[]|Collection|Model|null
     */
    public function show($id)
    {
        return $this->service->getById($id);
    }

    /**
     * @param $id
     * @param RequestAdvertisement $request
     * @return JsonResponse
     */
    public function update($id, RequestAdvertisement $request)
    {
       return $this->service->updateAdvertisement($id, $request);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
       return $this->service->deleteAdvertisement($id);
    }

}
