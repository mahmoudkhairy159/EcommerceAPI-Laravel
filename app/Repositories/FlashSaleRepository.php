<?php

namespace App\Repositories;

use App\Models\FlashSale;


use Illuminate\Support\Facades\DB;
use Prettus\Repository\Eloquent\BaseRepository;

class FlashSaleRepository extends BaseRepository
{

    public function model()
    {
        return FlashSale::class;
    }




    public function getFlashSale()
    {
        return $this->model
            ->with('flashSaleProducts')
            ->first();
    }



    public function updateOne(array $data)
    {

        try {
            DB::beginTransaction();

            // Use updateOrCreate with dynamic conditions
            $updated = $this->model->updateOrCreate(['id'=>1], $data);

            DB::commit();

            return $updated;
        } catch (\Throwable $th) {
            DB::rollBack();

            // Optionally log the error or handle it as needed
            return false;
        }


    }





}
