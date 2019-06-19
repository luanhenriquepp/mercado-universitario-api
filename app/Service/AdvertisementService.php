<?php

namespace App\Service;

use App\Advertisement;
use App\AdvertisementStatus;
use App\Constants\Constants;
use App\Constants\Messages;
use App\Http\Requests\RequestAdvertisement;
use App\Profile;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

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
        return Advertisement::with('user', 'category', 'advertisement_status')
            ->where('cd_user', auth()->user()->cd_user)
            ->paginate();
    }

    /**
     * @param $id
     * @return Advertisement|Advertisement[]|Builder|Builder[]|Collection|Model|null
     */
    public function getById($id)
    {
        return Advertisement::with('user', 'category')->findOrFail($id);
    }

    public function createFile($file)
    {
        $fileName = uniqid().date('Y-m-d').'.pdf';
        $directory = 'advertisement/';
        $file->advertisement_photo->storeAs('public/'.$directory, $fileName);
        return $directory.$fileName;
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
            'cd_user' => auth()->user()->cd_user,
            'cd_category' => $request->get('cd_category'),
            'cd_advertisement_status' => $request->input('cd_advertisement_status',
                AdvertisementStatus::AWAITINGAPPROVAL)
        ]);

        try {
            $advertisement->save();
            if ($advertisement) {
                $advertisement->advertisement_photo = $this->createFile($request);
                $advertisement->save();
            }
            return response()->json(
                [
                    'data' => $advertisement,
                    'success' => true,
                    'message' => Messages::MSG007
                ], Response::HTTP_CREATED);
        }catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'success'   => false,
                'message'   => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @param $id
     * @param RequestAdvertisement $request
     * @return JsonResponse
     */
    public function updateAdvertisement($id, RequestAdvertisement $request)
    {

        try {
            $advertisement = Advertisement::findOrFail($id);
            $advertisement->cd_advertisement_status = $request->input('cd_advertisement_status',
                AdvertisementStatus::AWAITINGAPPROVAL);
            $advertisement->update($request->all());
            return response()->json([
                'data' => $advertisement,
                'success' => true,
                'message' => Messages::MSG006
            ], Response::HTTP_OK);
        } catch (Exception $e) {
            Log::error($e);
            return response()->json([
                'success'  => false,
                'message'  =>  Messages::MSG005
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * @param $id
     * @return array
     */
    public function deleteAdvertisement($id)
    {
        try {
            Advertisement::findOrFail($id)->delete();
            return $response = [
                'success' => true,
                'message' => Messages::MSG008
            ];
        } catch (ModelNotFoundException $e) {
            Log::error($e);
            return response()->json([
                'success' => false,
                'message' => Messages::MSG002
            ], Response::HTTP_NOT_FOUND);
        }
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
                'success'   => false,
                'message' => Messages::MSG009
            ], Response::HTTP_UNAUTHORIZED);
        }

        $advertisement = Advertisement::with('user', 'advertisement_status')->find($id);

        if ($request->input('cd_advertisement_status') == false) {
            return response()->json([
                'success'  => false,
                'message'  => Messages::MSG010
            ], Response::HTTP_BAD_REQUEST);
        }

        $advertisement->cd_advertisement_status = $request->input('cd_advertisement_status');

        if ($advertisement->update()) {
            return response()->json([
                'data' => $advertisement,
                'success' => true,
                'message' => Messages::MSG011
            ], Response::HTTP_OK);
        }
        return response()->json([
            'success'   => false,
            'message' => Messages::MSG005
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
     * Método retorna todos os anúncios que estão aguardando aprovação
     * @return LengthAwarePaginator
     */
    public function awaitingApproval()
    {
        return $advertisement = Advertisement::with('user', 'advertisement_status','category')
            ->where('cd_advertisement_status', '=', AdvertisementStatus::AWAITINGAPPROVAL)
            ->paginate();
    }

    /**
     * @param $id
     * @return Advertisement|Advertisement[]|Builder|Builder[]|Collection|Model|null
     */
    public function pendingAdvertisement($id)
    {
        return $advertisement = Advertisement::with('user','category')->find($id);
    }
}
