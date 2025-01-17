<?php

namespace App\Repositories;

use App\Models\PaypalSetting;
use Prettus\Repository\Eloquent\BaseRepository;

class PaypalSettingRepository   extends BaseRepository
{

    public function model()
    {
        return PaypalSetting::class;
    }





    public function getPaypalSettings()
    {
        $paypalSettings = $this->model->get()->first();
        if (!$paypalSettings) {
            $paypalSettings = PaypalSetting::create([]);
        }
        return $paypalSettings;
    }

    public function createOne(array $data)
    {
        return $this->model->create($data);
    }

    public function updateOne(array $data)
    {
        $paypalSettings = $this->model->first();
        if (!$paypalSettings) {
            $paypalSettings = new PaypalSetting();
        }
        return $paypalSettings->update($data);
    }
}
