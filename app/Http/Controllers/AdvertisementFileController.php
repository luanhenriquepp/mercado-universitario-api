<?php

namespace App\Http\Controllers;

use App\AdvertisementFile;
use Illuminate\Http\Request;

class AdvertisementFileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        /**
         * TODO implementar em um outro momento, salvar imagem separadamente do anúncio
         */
        $advertisementFIle = new AdvertisementFile();
        $advertisementFIle->advertisement_photo = base64_encode(file_get_contents($request->file('advertisement_photo')
            ->path()));
        $advertisementFIle->save();

        return $advertisementFIle->cd_advertisement_file;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\AdvertisementFile  $advertisementFile
     * @return \Illuminate\Http\Response
     */
    public function show(AdvertisementFile $advertisementFile)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\AdvertisementFile  $advertisementFile
     * @return \Illuminate\Http\Response
     */
    public function edit(AdvertisementFile $advertisementFile)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\AdvertisementFile  $advertisementFile
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AdvertisementFile $advertisementFile)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\AdvertisementFile  $advertisementFile
     * @return \Illuminate\Http\Response
     */
    public function destroy(AdvertisementFile $advertisementFile)
    {
        //
    }
}
