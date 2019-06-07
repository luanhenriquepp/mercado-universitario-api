<?php
namespace App\Service;
use App\Advertisement;
use App\AdvertisementStatus;
use Illuminate\Http\Response;

class AdvertisementService {

    protected $advertisement;
    public function __construct(Advertisement $advertisement)
    {
        $this->advertisement = $advertisement;
    }

    /**
     * @param  $request
     * @return  $advertisement
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
        ], Response::HTTP_ACCEPTED);
        return $advertisement;
    }
}
