<?php
namespace App\Service;
use App\Advertisement;
use App\AdvertisementStatus;
use App\Http\Requests\RequestAdvertisement;
use App\Profile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class AdvertisementService {

    protected $advertisement;
    public function __construct(Advertisement $advertisement)
    {
        $this->advertisement = $advertisement;
    }

    /**
     * @param  $request
     * @return JsonResponse $advertisement
     */
    public function createAdvertisement($request)
    {
        $advertisement = $this->advertisement->create([
            'title'                     => $request->get('title'),
            'ds_advertisement'          => $request->get('ds_advertisement'),
            'price'                     => $request->get('price'),
            'advertisement_photo'       => $request->get('advertisement_photo'),
            'cd_user'                   => auth()->user()->cd_user,
            'cd_category'               => $request->get('cd_category'),
            'cd_advertisement_status'   => $request->input('cd_advertisement_status',
                AdvertisementStatus::AWAITINGAPPROVAL)
        ]);

        if ($advertisement->save()){
            return response()->json(
                [
                    'data' => $advertisement,
                    'success' => true,
                    'message' => 'Anúncio cadastrado com sucesso!'
                ], Response::HTTP_CREATED);
        }

        return response()->json([
            'success'   => false,
            'message'   => 'Ocorreu um erro no cadastro do anúncio!'
        ], Response::HTTP_BAD_REQUEST);
    }

    public function showAll()
    {
        $advertisements = Advertisement::with('user', 'category', 'advertisement_status')
            ->where('cd_user',auth()->user()->cd_user)
            ->paginate();
        return $advertisements;
    }

    public function updateStatusAdvertisement($id, RequestAdvertisement $request)
    {

       if ($this->checkUserProfile()){
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
                'data'      => $advertisement,
                'success'   => true,
                'message'   => 'Anúncio aprovado com sucesso!'
            ], Response::HTTP_OK);
        }
        return response()->json([
            'success' => false,
            'message' => 'Não foi possível atualizar os dados do anúncio!'
        ], Response::HTTP_BAD_REQUEST);
    }

    public function checkUserProfile()
    {
        $user = auth()->user();
        if ($user->cd_profile != Profile::ADMIN) {
            return true;
        }
        return false;
    }
}
