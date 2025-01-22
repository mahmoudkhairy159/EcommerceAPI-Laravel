<?php

namespace App\Repositories;

use App\Enums\ProductTypeEnum;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Wishlist;
use App\Traits\SoftDeletableTrait;
use Illuminate\Support\Facades\DB;
use Prettus\Repository\Eloquent\BaseRepository;

class ProductRepository extends BaseRepository
{
    use SoftDeletableTrait;
    public function model()
    {
        return Product::class;
    }
    public function getAll()
    {
        return $this->model
            ->filter(request()->all())
            ->orderBy('created_at', 'desc');
    }
    public function getAllByApprovalStatus($approvalStatus)
    {
        return $this->model
            ->where('approval_status', $approvalStatus)
            ->filter(request()->all())
            ->orderBy('created_at', 'desc');
    }
    public function getAllByVendorId($vendorId)
    {
        return $this->model
            ->where('vendor_id', $vendorId)
            ->filter(request()->all())
            ->orderBy('created_at', 'desc');
    }
    public function getStatistics()
    {
        return [
            "products_count" => $this->model->count(),
            "active_products_count" => $this->model->where('status', Product::STATUS_ACTIVE)->count(),
        ];
    }
    public function getStatisticsByVendorId($vendorId)
    {
        return [
            "products_count" => $this->model->where('vendor_id', $vendorId)->count(),
            "active_products_count" => $this->model->where('vendor_id', $vendorId)->where('status', Product::STATUS_ACTIVE)->count(),
        ];
    }


    public function getAllActive()
    {
        return $this->model
            ->filter(request()->all())
            ->where('status', Product::STATUS_ACTIVE)
            ->where('approval_status', Product::APPROVAL_APPROVED)
            ->orderBy('serial', 'asc');
    }
    public function getAllActiveByVendorId($vendorId)
    {
        return $this->model
            ->filter(request()->all())
            ->where('status', Product::STATUS_ACTIVE)
            ->where('approval_status', Product::APPROVAL_APPROVED)
            ->where('vendor_id', $vendorId)
            ->orderBy('serial', 'asc');
    }

    public function getFeaturedProducts()
    {
        return $this->model
            ->filter(request()->all())
            ->where('status', Product::STATUS_ACTIVE)
            ->where('approval_status', Product::APPROVAL_APPROVED)
            ->where('product_type', ProductTypeEnum::FEATURED_PRODUCT);
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
            'no_of_customers' => Wishlist::where('user_id', auth()->id())
                ->whereHas('items', function ($query) use ($id) {
                    $query->where('product_id', $id);
                })
                ->count(),
        ];
    }
    public function findBySlug(string $slug)
    {
        return $this->model->where('slug', $slug)
            ->with(['vendor', 'category', 'brand', 'productVariants', 'productImages', 'services', 'relatedProducts', 'accessories'])
            ->first();
    }
    public function getOneById(string $id)
    {
        return $this->model->where('id', $id)
            ->with(['vendor', 'category', 'brand', 'productVariants', 'productImages', 'services', 'relatedProducts', 'accessories'])
            ->first();
    }

    public function findActiveBySlug(string $slug)
    {

        return $this->model
            ->where('slug', $slug)
            ->with(['vendor', 'category', 'brand', 'productVariants', 'productImages', 'services', 'relatedProducts', 'accessories'])
            ->where('status', Product::STATUS_ACTIVE)
            ->where('approval_status', Product::APPROVAL_APPROVED)
            ->first();
    }
    public function getOneActiveById(string $id)
    {

        return $this->model
            ->where('id', $id)
            ->with(['vendor', 'category', 'brand', 'productVariants', 'productImages', 'services', 'relatedProducts', 'accessories'])
            ->where('status', Product::STATUS_ACTIVE)
            ->where('approval_status', Product::APPROVAL_APPROVED)
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
    public function changeApprovalStatus(int $id, $data)
    {
        try {
            DB::beginTransaction();
            $product = $this->model->findOrFail($id);
            $updated = $product->update($data);
            DB::commit();
            return $updated;
        } catch (\Throwable $th) {

            DB::rollBack();
            return false;
        }
    }
    public function updateSerial(array $data, int $id)
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
    public function updateProductType(int $id, $data)
    {
        try {
            DB::beginTransaction();
            $product = $this->model->findOrFail($id);
            $updated = $product->update($data);
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
            $deleted = $product->delete();

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
    public function getOnlyTrashedByVendorId($vendorId)
    {
        return $this->model
            ->onlyTrashed()
            ->where('vendor_id',$vendorId)
            ->filter(request()->all())
            ->orderBy('deleted_at', 'desc');
    }
    public function checkProductOwnership($id, $relation)
    {
        return $this->model
            ->where('vendor_id', auth()->guard('vendor-api')->id())
            ->whereHas($relation, function ($query) use ($id) {
                $query->where('id', $id);
            })
            ->exists();
    }

}
