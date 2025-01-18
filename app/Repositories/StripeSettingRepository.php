<?php

namespace App\Repositories;

use App\Models\StripeSetting;
use Prettus\Repository\Eloquent\BaseRepository;

class StripeSettingRepository   extends BaseRepository
{

    public function model()
    {
        return StripeSetting::class;
    }





    public function getStripeSettings()
    {
        $stripeSettings = $this->model->get()->first();
        if (!$stripeSettings) {
            $stripeSettings = StripeSetting::create([]);
        }
        return $stripeSettings;
    }

    public function createOne(array $data)
    {
        return $this->model->create($data);
    }

    public function updateOne(array $data)
    {
        $stripeSettings = $this->model->first();
        if (!$stripeSettings) {
            $stripeSettings = new StripeSetting();
        }
        return $stripeSettings->update($data);
    }
}
