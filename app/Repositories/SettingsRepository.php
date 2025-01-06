<?php

namespace App\Repositories;

use App\Models\Setting;
use Prettus\Repository\Eloquent\BaseRepository;

class SettingsRepository   extends BaseRepository
{

    public function model()
    {
        return Setting::class;
    }





    public function getSettings()
    {
        $settings = $this->model->get()->first();
        if (!$settings) {
            $settings = Setting::create([]);
        }
        return $settings;
    }

    public function createOne(array $data)
    {
        if (request()->hasFile('logo')) {
            $data['logo'] = $this->model->uploadImage(request()->file('logo'), Setting::FILES_DIRECTORY);
        }
        if (request()->hasFile('logo_light')) {
            $data['logo_light'] = $this->uploadImage(request()->file('logo_light'),Setting::FILES_DIRECTORY);
        }
        return $this->model->create($data);
    }

    public function updateOne(array $data)
    {
        $settings = $this->model->first();
        if (!$settings) {
            $settings = new Setting();
        }
        if (request()->hasFile('logo')) {
            if ($settings->logo) {
                $this->deleteFile($settings->logo);
            }
            $data['logo'] = $this->uploadImage(request()->file('logo'), Setting::FILES_DIRECTORY);
        }

        if (request()->hasFile('logo_light')) {
            if ($settings->logo_light) {
                $this->deleteFile($settings->logo_light);
            }
            $data['logo_light'] = $this->uploadImage(request()->file('logo_light'), Setting::FILES_DIRECTORY, );
        }

        return $settings->update($data);
    }
}
