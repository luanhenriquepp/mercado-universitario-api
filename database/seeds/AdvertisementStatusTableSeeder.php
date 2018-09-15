<?php

use Illuminate\Database\Seeder;

class AdvertisementStatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $advertisementStatus = new \App\AdvertisementStatus();
        $advertisementStatus->cd_advertisement_status = \App\AdvertisementStatus::APPROVED;
        $advertisementStatus->ds_advertisement_status = 'Aprovado';
        $advertisementStatus->save();

        $advertisementStatus = new \App\AdvertisementStatus();
        $advertisementStatus->cd_advertisement_status = \App\AdvertisementStatus::REPROVED;
        $advertisementStatus->ds_advertisement_status = 'Reprovado';
        $advertisementStatus->save();

        $advertisementStatus = new \App\AdvertisementStatus();
        $advertisementStatus->cd_advertisement_status = \App\AdvertisementStatus::AWAITINGAPPROVAL;
        $advertisementStatus->ds_advertisement_status = 'Aguardando Aprovação';
        $advertisementStatus->save();

        $advertisementStatus = new \App\AdvertisementStatus();
        $advertisementStatus->cd_advertisement_status = \App\AdvertisementStatus::CANCELED;
        $advertisementStatus->ds_advertisement_status = 'Cancelado';
        $advertisementStatus->save();
    }
}
