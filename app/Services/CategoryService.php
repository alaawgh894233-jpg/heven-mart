<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class CategoryService
{
    public function __construct()
    {

    }


    public function create(array $data):Category
    {
        DB::beginTransaction();
        try{
        if(isset($data['image'])){
            $data['image'] = $this->uploadImage($data['image']);
        }
        $category = Category::create($data);
        DB::commit();

    }catch (\Exception $exception){
            DB::rollBack();
            throw new \Exception('created failed');
        }
        return $category;
    }

    public function update(Category $category, array $data): Category
     {
         if(!empty($data['parent_id']) && $this->isCircularParent($category->id , $data['parent_id'])){
             throw new \Exception('can not set child parent');
         }
         DB::beginTransaction();
         try {
             if (isset($data['image'])) {
                 if ($category->image && Storage::disk('public')->exists($category->image)) {
                     $this->deleteImage($category->image);
                 }
                 $data['image'] = $this->uploadImage($data['image']);
             }
             $category->update($data);
             DB::commit();
         }catch (\Exception $exception){
             DB::rollBack();
             throw new \Exception('updated failed');
         }
         return $category;
     }

    public function toggleStatus(Category $category): Category
    {
        DB::beginTransaction();
        try {
            $category->update([
                'is_active' => !$category->is_active,
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('changed failed');
        }
        return $category;
    }


    public function delete(Category $category):void
    {
        if ($category->children()->exists()) {
            throw new \Exception('cannot delete parent category');
        }
        if($category->products()->exists()){
            throw new \Exception('cannot delete category because it has products');
        }
        DB::beginTransaction();
        try {
            $this->imageService->delete($category->image);
            $category->delete();
            DB::commit();
        }catch (\Exception $exception){
            DB::rollBack();
            throw new \Exception('deleted failed');
        }
    }

    public function getValidParentsFor(Category $category):Collection
    {
        // 1. اجلب كل أولاده وأحفاده
        $excludedIds = $this->getDescendantIds($category);

        // 2. أضف التصنيف نفسه للقائمة
        $excludedIds[] = $category->id;

        // 3. استرجع كل التصنيفات التي لا تقع ضمن هذه القائمة
        return Category::whereNotIn('id', $excludedIds)->where->get();
    }




     public function getAllForAdmin(int $per_page = 15, int $page = 1, $filters)
     {
         return Category::query()
             ->filter($filters)
             ->when(!is_null($filters['is_active']), fn($q) => $q->where('is_active', $filters['is_active']))
             ->paginate($per_page, ['*'], 'page', $page);
     }


     //for select when create product
    public function getLeafCategories(string $lang)
    {
        $categories = Category::doesntHave('children')->with('parent')->where('is_active',true)->get();

        return $categories->map(function ($cat) use ($lang) {
            return [
                'id' => $cat->id,
                'name' => $cat->{'name_'.$lang},
                'image' => asset('storage/'. $cat->image),
                'full_path' => $cat->getFullPathAttribute($lang),
            ];
        });
    }

    public function getParents(string $lang)
    {
        $categories = Category::where('parent_id', null)->where('is_active',true)->get();
        return $categories->map(function ($cat) use ($lang) {
            return [
                'id' => $cat->id,
                'name' => $cat->{'name_'.$lang},
                'image' => asset('storage/'. $cat->image),
                'is_leaf' => $cat->children()->where('is_active',true)->count() > 0? false: true,
            ];
        });
    }

    public function getChildren(Category $category, string $lang)
    {
        $children = $category->children()->where('is_active',true)->get();
        return $children->map(function ($child) use ($lang) {
            return [
                'id' => $child->id,
                'name' => $child->{'name_'.$lang},
                'image' => asset('storage/'. $child->image),
                'is_leaf' => $child->children()->where('is_active', true)->count() > 0? false: true,
            ];
        });
    }


    private function isCircularParent($category_id, int $newParentId)
    {
        $new = $newParentId;
        while ($new != null) {
            if($category_id == $new) {
                return true;
            }
            $parent = Category::find($new);
            $new = $parent ? $parent->parent_id : null;
            Log::info("parent".$new);
        }
        return false;
    }


    public function getDescendantIds(Category $category): array
    {
        $ids = [];
        foreach ($category->children as $child) {
            $ids[] = $child->id;
            // استدعاء نفسها لتجلب أولاد هذا الطفل (أي الأحفاد)
            $ids = array_merge($ids, $this->getDescendantIds($child));
        }
        return $ids;
    }


    private function uploadImage($image): string
    {
        return $image->store('categories', 'public');
    }

    private function deleteImage($image)
    {
        Storage::disk('public')->delete($image);
    }


}
