<?php

namespace App\Services;
use App\Models\Brand;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BrandService
{

    public function create(array $data): Brand
    {
        DB::beginTransaction();
        try {
            if (isset($data['image'])) {
                $data['image'] = $this->uploadImage($data['image']);
            }

            $brand = Brand::create($data);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('create failed');
        }
        return $brand;
    }


    public function update(Brand $brand, array $data): Brand
    {
        DB::beginTransaction();
        try {
            if (isset($data['image'])) {
                if($brand->image && Storage::disk('public')->exists($brand->image))
                {
                    $this->deleteImage($brand->image);
                }
                $data['image'] = $this->uploadImage($data['image']);
            }
            $brand->update($data);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('updated failed');
        }
        return $brand;
    }


    public function delete(Brand $brand): void
    {
        DB::beginTransaction();
        try {
            if($brand->image && Storage::disk('public')->exists($brand->image)){
                $this->deleteImage($brand->image);
            }
            $brand->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('deleted failed');
        }
    }

    /**
     * تغيير الحالة (تفعيل/تعطيل)
     */
    public function toggleStatus(Brand $brand): Brand
    {
        DB::beginTransaction();
        try {
            $brand->update([
                'is_active' => !$brand->is_active,
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('changed failed');
        }
        return $brand;
    }


    public function getForAdmin(?string $search = null, ?bool $isActive = null, int $page = 1, int $perPage = 15)
    {
        return Brand::query()
            ->filter($search)
            ->when(!is_null($isActive), fn($q) => $q->where('is_active', $isActive))
            ->paginate($perPage, ['*'], 'page', $page);
    }


    public function getForUser(?string $search = null)
    {
        return Brand::query()
            ->active()
            ->filter($search)
            ->get();
    }


    private function uploadImage($image): string
    {
        return $image->store('brands', 'public');
    }

    private function deleteImage($image)
    {
        Storage::disk('public')->delete($image);
    }


}
