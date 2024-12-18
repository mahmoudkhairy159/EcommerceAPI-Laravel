<?php

namespace App\Repositories;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Wishlist;
use Illuminate\Support\Facades\DB;
use Prettus\Repository\Eloquent\BaseRepository;

class ProductRepository extends BaseRepository
{
    public function model()
    {
        return Product::class;
    }
    public function getAll()
    {
        return $this->model
            ->filter(request()->all())
            ->with(['category', 'brand'])
            ->orderBy('created_at', 'desc');
    }
    public function getStatistics()
    {
        return [
            "products_count" => $this->model->count(),
            "active_products_count" => $this->model->where('status', Product::STATUS_ACTIVE)->count(),
        ];
    }
    public function getStatisticsById($id)
    {

        // $date_type = $request->date_type;

        return [
            // "product_total_order" => Order$this->model->where("product_id", $id)->FilterByOrderReportDate($date_type)->sum("selling_price"),
            // "product_views_count" => $this->model->find($id)->no_of_views,
            // "total_orders_count" => Order::query()->FilterByProductId($id)->count(),
            // "pending_orders_count" => Order::query()->FilterByProductId($id)->FilterByStatus("Pending")->FilterByOrderReportDate($date_type)->count(),
            // "completed_orders_count" => Order::query()->FilterByProductId($id)->FilterByStatus("Completed")->FilterByOrderReportDate($date_type)->count(),
            // "cancelled_orders_count" => Order::query()->FilterByProductId($id)->FilterByStatus("Cancelled")->FilterByOrderReportDate($date_type)->count(),
            // "returned_orders_count" => Order::query()->FilterByProductId($id)->FilterByStatus("Returned")->FilterByOrderReportDate($date_type)->count(),
            // "damaged_orders_count" => Order::query()->FilterByProductId($id)->FilterByStatus("Damaged")->FilterByOrderReportDate($date_type)->count(),
        ];

    }
    public function getAllActive()
    {
        return $this->model
            ->filter(request()->all())
            ->with(['category', 'brand'])
            ->where('status', Product::STATUS_ACTIVE)
            ->orderBy('created_at', 'desc');
    }

    public function getFeaturedProducts()
    {
        return $this->model
            ->filter(request()->all())
            ->with(['category', 'brand'])
            ->where('status', Product::STATUS_ACTIVE)
            ->where('is_featured', 1)
            ->inRandomOrder();
    }
    public function getLowQuantityAlertProductsCount()
    {
        return [
            'low_quantity_products_count' => $this->model->whereColumn('quantity', '<', 'alert_stock_quantity')->count(),
        ];
    }

    public function getFavoriteCustomersCountByProductId($id)
    {
        return [
            'no_of_customers' => Wishlist::where('product_id', $id)->count(),
        ];
    }
    public function findBySlug(string $slug)
    {
        return $this->model->where('slug', $slug)
            ->with(['category', 'brand', 'productImages', 'services', 'relatedProducts', 'accessories'])
            ->first();
    }
    public function getOneById(string $id)
    {
        return $this->model->where('id', $id)
            ->with(['category', 'brand', 'productImages', 'services', 'relatedProducts', 'accessories'])
            ->first();
    }

    public function findActiveBySlug(string $slug)
    {

        return $this->model
            ->where('slug', $slug)
            ->with(['category', 'brand', 'productImages', 'services', 'relatedProducts', 'accessories'])
            ->where('status', Product::STATUS_ACTIVE)
            ->first();
    }
    public function getOneActiveById(string $id)
    {

        return $this->model
            ->where('id', $id)
            ->with(['category', 'brand', 'productImages', 'services', 'relatedProducts', 'accessories'])
            ->where('status', Product::STATUS_ACTIVE)
            ->first();
    }
    public function createOne(array $data)
    {
        try {
            DB::beginTransaction();

            if (request()->hasFile('image')) {
                $data['image'] = $this->uploadFile(request()->file('image'), Product::FILES_DIRECTORY);
            }
            if (request()->hasFile('product_images')) {
                $images = request()->file('product_images');
                $uploadedImages = [];
                foreach ($images as $image) {
                    $uploadedImages[] = $this->uploadFile($image, ProductImage::FILES_DIRECTORY);
                }
            }
            $created = $this->model->create($data);
            if (isset($uploadedImages)) {
                foreach ($uploadedImages as $imagePath) {
                    ProductImage::create([
                        'product_id' => $created->id,
                        'image' => $imagePath,
                    ]);
                }
            }
            if (isset($data['services'])) {
                $created->services()->sync($data['services']);
            }
            if (isset($data['relatedProductIds']) && is_array($data['relatedProductIds'])) {
                $this->syncRelatedProducts($data, $created->id);
            }
            if (isset($data['productAccessoriesIds']) && is_array($data['productAccessoriesIds'])) {
                $this->syncProductAccessories($data, $created->id);
            }
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
                $data['image'] = $this->uploadFile(request()->file('image'), Product::FILES_DIRECTORY);
            }
            $updated = $product->update($data);
            if (isset($data['services'])) {
                $product->services()->sync($data['services']);
            }
            if (isset($data['relatedProductIds']) && is_array($data['relatedProductIds'])) {
                $this->syncRelatedProducts($data, $product->id);
            }
            if (isset($data['productAccessoriesIds']) && is_array($data['productAccessoriesIds'])) {
                $this->syncProductAccessories($data, $product->id);
            }
            DB::commit();

            return $updated;
        } catch (\Throwable $th) {

            DB::rollBack();
            return false;
        }
    }
    public function changeStatus(int $id)
    {
        try {
            DB::beginTransaction();
            $product = $this->model->findOrFail($id);
            $product->status = $product->status == Product::STATUS_ACTIVE ? Product::STATUS_INACTIVE : Product::STATUS_ACTIVE;
            $updated = $product->save();
            DB::commit();
            return $updated;
        } catch (\Throwable $th) {

            DB::rollBack();
            return false;
        }
    }
    public function updateRank(array $data, int $id)
    {
        try {
            DB::beginTransaction();
            $model = $this->model->findOrFail($id);
            $updated = $model->update($data);
            DB::commit();

            return $updated;
        } catch (\Throwable $th) {

            DB::rollBack();
            return false;
        }
    }
    public function updateFeaturedStatus(int $id)
    {
        try {
            DB::beginTransaction();
            $product = $this->model->findOrFail($id);
            $product->is_featured = $product->is_featured == 1 ? 0 : 1;
            $updated = $product->save();
            DB::commit();

            return $updated;
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
            // if ($product->image) {
            //     $this->deleteFile($product->image);
            // }
            // $deleted = $product->delete();
            $product->status = Product::STATUS_INACTIVE;
            $deleted = $product->save();
            DB::commit();
            return $deleted;
        } catch (\Throwable $th) {

            DB::rollBack();
            return false;
        }
    }
    public function deleteImage(int $id)
    {
        try {
            DB::beginTransaction();

            $product = $this->model->findOrFail($id);
            if ($product->image) {
                $this->deleteFile($product->image);
                $product->image = null;
                $product->save();
            }
            DB::commit();
            return true;
        } catch (\Throwable $th) {

            DB::rollBack();
            return false;
        }
    }

    /*********************************Related_products***************************************/
    public function addRelatedProducts(array $data)
    {
        try {
            DB::beginTransaction();
            $product = $this->model->findOrFail($data['product_id']);
            $product->relatedProducts()->syncWithoutDetaching($data['relatedProductIds']);

            // Ensure bidirectional relationship
            foreach ($data['relatedProductIds'] as $relatedProductId) {
                $relatedProduct = $this->model->findOrFail($relatedProductId);
                $relatedProduct->relatedProducts()->syncWithoutDetaching($data['product_id']);
            }
            DB::commit();
            return true;
        } catch (\Throwable $th) {
            DB::rollBack();
            return false;
        }
    }

    // public function syncRelatedProducts(array $data)
    // {
    //     try {
    //         DB::beginTransaction();
    //         $product = $this->model->findOrFail($data['product_id']);

    //         // Detach all old related products
    //         $currentRelatedProducts = $product->relatedProducts()->pluck('related_product_id')->toArray();
    //         foreach ($currentRelatedProducts as $relatedProductId) {
    //             $relatedProduct = $this->model->findOrFail($relatedProductId);
    //             $relatedProduct->relatedProducts()->detach($data['product_id']);
    //         }

    //         // Sync the new related products
    //         $product->relatedProducts()->sync($data['relatedProductIds']);

    //         // Ensure bidirectional relationship
    //         foreach ($data['relatedProductIds'] as $relatedProductId) {
    //             $relatedProduct = $this->model->findOrFail($relatedProductId);
    //             $relatedProduct->relatedProducts()->syncWithoutDetaching($data['product_id']);
    //         }

    //         DB::commit();
    //         return true;
    //     } catch (\Throwable $th) {
    //         DB::rollBack();
    //         return false;
    //     }
    // }
    public function syncRelatedProducts(array $data, $productId)
    {
        try {
            DB::beginTransaction();
            $product = $this->model->findOrFail($productId);

            // Detach all old related products
            $currentRelatedProducts = $product->relatedProducts()->pluck('related_product_id')->toArray();
            foreach ($currentRelatedProducts as $relatedProductId) {
                $relatedProduct = $this->model->findOrFail($relatedProductId);
                $relatedProduct->relatedProducts()->detach($productId);
            }

            // Sync the new related products
            $product->relatedProducts()->sync($data['relatedProductIds']);

            // Ensure bidirectional relationship
            foreach ($data['relatedProductIds'] as $relatedProductId) {
                $relatedProduct = $this->model->findOrFail($relatedProductId);
                $relatedProduct->relatedProducts()->syncWithoutDetaching($productId);
            }

            DB::commit();
            return true;
        } catch (\Throwable $th) {
            DB::rollBack();
            return false;
        }
    }

    public function removeRelatedProducts(array $data)
    {
        try {
            DB::beginTransaction();
            $product = $this->model->findOrFail($data['product_id']);
            $product->relatedProducts()->detach($data['relatedProductIds']);

            // Ensure bidirectional relationship
            foreach ($data['relatedProductIds'] as $relatedProductId) {
                $relatedProduct = $this->model->findOrFail($relatedProductId);
                $relatedProduct->relatedProducts()->detach($data['product_id']);
            }
            DB::commit();
            return true;
        } catch (\Throwable $th) {
            DB::rollBack();
            return false;
        }
    }

    public function getRelatedProducts(Product $product, $limit = 4)
    {
        return $product->relatedProducts()->inRandomOrder()->limit($limit);
    }
    public function getPaginatedRelatedProducts(Product $product)
    {
        return $product->relatedProducts();
    }
    /*********************************Related_products***************************************/
    /*********************************Related_services***************************************/
    public function addRelatedServices(array $data)
    {
        try {
            DB::beginTransaction();
            $product = $this->model->findOrFail($data['product_id']);
            $product->services()->syncWithoutDetaching($data['relatedServiceIds']);
            DB::commit();
            return true;
        } catch (\Throwable $th) {
            DB::rollBack();
            return false;
        }
    }
    public function syncRelatedServices(array $data)
    {
        try {
            DB::beginTransaction();
            $product = $this->model->findOrFail($data['product_id']);
            // Sync the new related services
            $product->services()->sync($data['relatedServiceIds']);
            DB::commit();
            return true;
        } catch (\Throwable $th) {
            DB::rollBack();
            return false;
        }
    }

    public function removeRelatedServices(array $data)
    {
        try {
            DB::beginTransaction();
            $product = $this->model->findOrFail($data['product_id']);
            $product->services()->detach($data['relatedServiceIds']);
            DB::commit();
            return true;
        } catch (\Throwable $th) {
            DB::rollBack();
            return false;
        }
    }

    public function getRelatedServices(Product $product, $limit = 4)
    {
        return $product->services()->inRandomOrder()->limit($limit);
    }
    public function getPaginatedRelatedServices(Product $product)
    {
        return $product->services();
    }
    /*********************************Related_services***************************************/

/*********************************product_accessories***************************************/
    public function addProductAccessories(array $data)
    {
        try {
            DB::beginTransaction();
            $product = $this->model->findOrFail($data['product_id']);
            $product->accessories()->syncWithoutDetaching($data['ProductAccessoriesIds']);

            // Ensure bidirectional relationship
            foreach ($data['ProductAccessoriesIds'] as $productAccessoryId) {
                $productAccessory = $this->model->findOrFail($productAccessoryId);
                $productAccessory->accessories()->syncWithoutDetaching($data['product_id']);
            }
            DB::commit();
            return true;
        } catch (\Throwable $th) {
            DB::rollBack();
            return false;
        }
    }
    // public function syncProductAccessories(array $data)
    // {
    //     try {
    //         DB::beginTransaction();
    //         $product = $this->model->findOrFail($data['product_id']);

    //         // Detach all old product accessories
    //         $currentProductAccessories = $product->accessories()->pluck('accessory_id')->toArray();
    //         foreach ($currentProductAccessories as $productAccessoryId) {
    //             $productAccessory = $this->model->findOrFail($productAccessoryId);
    //             $productAccessory->accessories()->detach($data['product_id']);
    //         }

    //         // Sync the new related products
    //         $product->accessories()->sync($data['productAccessoriesIds']);

    //         // Ensure bidirectional relationship
    //         foreach ($data['productAccessoriesIds'] as $productAccessoryId) {
    //             $productAccessory = $this->model->findOrFail($productAccessoryId);
    //             $productAccessory->accessories()->syncWithoutDetaching($data['product_id']);
    //         }

    //         DB::commit();
    //         return true;
    //     } catch (\Throwable $th) {
    //         DB::rollBack();
    //         return false;
    //     }
    // }
    public function syncProductAccessories(array $data, $productId)
    {
        try {
            DB::beginTransaction();
            $product = $this->model->findOrFail($productId);

            // Detach all old product accessories
            $currentProductAccessories = $product->accessories()->pluck('accessory_id')->toArray();
            foreach ($currentProductAccessories as $productAccessoryId) {
                $productAccessory = $this->model->findOrFail($productAccessoryId);
                $productAccessory->accessories()->detach($productId);
            }

            // Sync the new related products
            $product->accessories()->sync($data['productAccessoriesIds']);

            // Ensure bidirectional relationship
            foreach ($data['productAccessoriesIds'] as $productAccessoryId) {
                $productAccessory = $this->model->findOrFail($productAccessoryId);
                $productAccessory->accessories()->syncWithoutDetaching($productId);
            }

            DB::commit();
            return true;
        } catch (\Throwable $th) {
            DB::rollBack();
            return false;
        }
    }

    public function removeProductAccessories(array $data)
    {
        try {
            DB::beginTransaction();
            $product = $this->model->findOrFail($data['product_id']);
            $product->accessories()->detach($data['productAccessoriesIds']);

            // Ensure bidirectional relationship
            foreach ($data['productAccessoriesIds'] as $productAccessoryId) {
                $productAccessory = $this->model->findOrFail($productAccessoryId);
                $productAccessory->accessories()->detach($data['product_id']);
            }
            DB::commit();
            return true;
        } catch (\Throwable $th) {
            DB::rollBack();
            return false;
        }
    }

    public function getProductAccessories(Product $product, $limit = 4)
    {
        return $product->accessories()->inRandomOrder()->limit($limit);
    }
    public function getPaginatedProductAccessories(Product $product)
    {
        return $product->accessories();
    }
/*********************************product_accessories***************************************/
}
