<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use App\Models\ProductImage;
use Prettus\Repository\Eloquent\BaseRepository;

class ProductImageRepository extends BaseRepository
{
    public function model()
    {
        return ProductImage::class;
    }
    public function getByProductId($product_id)
    {
        return $this->model
            ->where('product_id', $product_id);
    }

    public function createOne(array $data)
    {
        try {
            DB::beginTransaction();

            if (request()->hasFile('image')) {
                $data['image'] = $this->uploadFile(request()->file('image'), ProductImage::FILES_DIRECTORY);
            }
            $created = $this->model->create($data);
            DB::commit();

            return $created;
        } catch (\Throwable $th) {

            DB::rollBack();
            return false;
        }
    }
    public function updateOne(array $data, int $id)
    {
        try {
            DB::beginTransaction();
            $product = $this->model->findOrFail($id);
            if (request()->hasFile('image')) {
                if ($product->image) {
                    $this->deleteFile($product->image);
                }
                $data['image'] = $this->uploadFile(request()->file('image'), ProductImage::FILES_DIRECTORY);
            }
            $updated = $product->update($data);

            DB::commit();

            return $product->refresh();
        } catch (\Throwable $th) {

            DB::rollBack();
            return false;
        }
    }
    //delete by product
    public function deleteOne(int $id)
    {
        try {
            DB::beginTransaction();

            $product = $this->model->findOrFail($id);
            if ($product->image) {
                $this->deleteFile($product->image);
            }
            $deleted = $product->delete();
            DB::commit();
            return $deleted;
        } catch (\Throwable $th) {

            DB::rollBack();
            return false;
        }
    }


}
