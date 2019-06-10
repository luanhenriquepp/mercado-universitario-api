<?php

namespace App\Service;

use App\Advertisement;
use App\AdvertisementStatus;
use App\Http\Requests\RequestAdvertisement;
use App\Profile;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class AdvertisementService
{

    protected $advertisement;

    public function __construct(Advertisement $advertisement)
    {
        $this->advertisement = $advertisement;
    }

    /**
     * @return LengthAwarePaginator
     */
    public function getAll()
    {
        $advertisements = Advertisement::with('user', 'category', 'advertisement_status')
            ->where('cd_user', auth()->user()->cd_user)
            ->paginate();
        return $advertisements;
    }

    /**
     * @param $id
     * @return Advertisement|Advertisement[]|Builder|Builder[]|Collection|Model|null
     */
    public function getById($id)
    {
        return Advertisement::with('user', 'category')->find($id);
    }

    /**
     * @param  $request
     * @return JsonResponse $advertisement
     */
    public function createAdvertisement($request)
    {
        $advertisement = $this->advertisement->create([
            'title' => $request->get('title'),
            'ds_advertisement' => $request->get('ds_advertisement'),
            'price' => $request->get('price'),
            'advertisement_photo' => $request->get('advertisement_photo'),
            'cd_user' => auth()->user()->cd_user,
            'cd_category' => $request->get('cd_category'),
            'cd_advertisement_status' => $request->input('cd_advertisement_status',
                AdvertisementStatus::AWAITINGAPPROVAL)
        ]);

        if ($advertisement->save()) {
            return response()->json(
                [
                    'data' => $advertisement,
                    'success' => true,
                    'message' => 'Anúncio cadastrado com sucesso!'
                ], Response::HTTP_CREATED);
        }

        return response()->json([
            'success' => false,
            'message' => 'Ocorreu um erro no cadastro do anúncio!'
        ], Response::HTTP_BAD_REQUEST);
    }

    /**
     * @param $id
     * @param RequestAdvertisement $request
     * @return JsonResponse
     */
    public function updateAdvertisement($id, RequestAdvertisement $request)
    {
        $advertisement = Advertisement::find($id);
        if (!$advertisement) {
            return response()->json([
                'success' => false,
                'message' => 'Nenhum anúncio foi encontrado com esse código!'
            ], Response::HTTP_FOUND);
        }
        $advertisement->cd_advertisement_status = $request->input('cd_advertisement_status',
            AdvertisementStatus::AWAITINGAPPROVAL);
        if ($advertisement->update($request->all())) {
            return response()->json([
                'data' => $advertisement,
                'success' => true,
                'message' => 'Anúncio atualizado com sucesso!'
            ], Response::HTTP_OK);
        }

        return response()->json([
            'success' => false,
            'message' => 'Não foi possível atualizar o anúncio!'
        ], Response::HTTP_BAD_REQUEST);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function deleteAdvertisement($id)
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
            'message' => 'Não foi possível excluir o anúncio!'
        ], Response::HTTP_FORBIDDEN);
    }

    /**
     * @param $id
     * @param RequestAdvertisement $request
     * @return JsonResponse
     */
    public function updateAdvertisementStatus($id, RequestAdvertisement $request)
    {

        if ($this->checkUserProfile()) {
            return response()->json([
                'success' => false,
                'message' => 'Acesso negado'
            ], Response::HTTP_UNAUTHORIZED);
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
                'data' => $advertisement,
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
     * @return bool
     */
    public function checkUserProfile()
    {
        $user = auth()->user();
        if ($user->cd_profile != Profile::ADMIN) {
            return true;
        }
        return false;
    }

    /**
     * @return LengthAwarePaginator
     */
    public function publicPageAll()
    {
        return $advertisements = Advertisement::with('user', 'category',
            'advertisement_status', 'user.address', 'user.universities')
            ->where('cd_advertisement_status', '=', AdvertisementStatus::APPROVED)
            ->paginate();
    }

    /**
     * @return LengthAwarePaginator
     */
    public function awaitingApproval()
    {
        return $advertisement = Advertisement::with('user', 'advertisement_status','category')
            ->where('cd_advertisement_status', '=', AdvertisementStatus::AWAITINGAPPROVAL)
            ->paginate();
    }

    public function pendingAdvertisement($id)
    {
        return $advertisement = Advertisement::with('user','category')->find($id);
    }
}
