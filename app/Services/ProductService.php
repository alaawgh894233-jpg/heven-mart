<?php

namespace App\Services;

use App\Models\AttributeValue;
use App\Models\ImageProduct;
use App\Models\Product;
use App\Models\Attribute;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductService
{
    public function getProducts(Request $request)
    {
        $lang = $request->header('lang', 'en');

        $query = Product::query()
            ->active()
            ->orderByDesc('num_of_purchase')
            ->priceBetween($request->input('price_min'), $request->input('price_max'))
            ->rateBetween($request->input('min_rate'), $request->input('max_rate'))
            ->with(['primaryImage', 'attributeValues.attribute']);

        $products = $query->paginate(20);

        $filteredAttributes = $request->input('attributes', []);

        $formattedProducts = $products->map(function ($product) use ($lang, $filteredAttributes) {

            $attributes = $product->attributeValues->map(function ($val) use ($lang) {
                return [
                    'attribute_name' => $val->attribute->{"name_$lang"},
                    'value' => $val->{"value_$lang"},
                    'price_impact' => $val->pivot->price_impact // استخدم pivot
                ];
            });


            $selectedValueIds = array_keys($filteredAttributes);

            return [
                'id' => $product->id,
                'name' => $product->{"name_$lang"},
                'price' => $product->getPriceWithAttributes($selectedValueIds),
                'discount' => $product->discount,
                'rate' => $product->rate,
                'num_of_purchase' => $product->num_of_purchase,
                'image' => $product->primaryImage ? asset('storage/' . $product->primaryImage->url_image) : null,
                'stock' => $product->stock,
                'unit' => $product->{"unit_$lang"},
                'is_featured' => $product->is_featured,
                'attributes' => $attributes,
            ];
        });

        return [
            'products' => $formattedProducts,
            'pagination' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
            ],
        ];
    }

    public function getProductById($id, Request $request)
    {
        $lang = $request->header('lang', 'en');

        $product = Product::with([
            'reviews.user',
            'attributeValues.attribute',
            'primaryImage',
            'images',
            'store',
            'category'
        ])->findOrFail($id);

        $attributes = $product->attributeValues
            ->groupBy('attribute_id')
            ->map(function ($values, $attributeId) use ($lang) {
                return [
                    'id' => $attributeId,
                    'name' => optional($values->first()->attribute)->{"name_$lang"},
                    'values' => $values->map(fn($val) => $val->{"value_$lang"})->unique()->values(),
                ];
            })->values();

        $reviews = $product->reviews->map(function ($review) {
            return [
                'user' => $review->user->name,
                'rating' => $review->rating,
                'review' => $review->review,
                'created_at' => $review->created_at->toDateTimeString(),
            ];
        });

        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->active()
            ->with('primaryImage')
            ->take(5)
            ->get()
            ->map(function ($related) use ($lang) {
                return [
                    'id' => $related->id,
                    'name' => $related->{"name_$lang"},
                    'price' => $related->price,
                    'discount' => $related->discount,
                    'rate' => $related->rate,
                    'primary_image' => $related->primaryImage ? asset('storage/' . $related->primaryImage->url_image) : null,
                ];
            });

        return [
            'id' => $product->id,
            'name' => $product->{"name_$lang"},
            'description' => $product->{"description_$lang"},
            'details' => $product->{"details_$lang"},
            'price' => $product->price,
            'discount' => $product->discount,
            'rate' => $product->rate,
            'num_of_purchase' => $product->num_of_purchase,
            'stock' => $product->stock,
            'unit' => $product->{"unit_$lang"},
            'primary_image' => $product->primaryImage ? asset('storage/' . $product->primaryImage->url_image) : null,
            'images' => $product->images->map(fn($img) => asset('storage/' . $img->url_image)),
            'category' => [
                'id' => $product->category->id,
                'name' => $product->category->{"name_$lang"},
            ],
            'store' => [
                'id' => $product->store->id,
                'name' => $product->store->{"name_$lang"},
                'logo' => $product->store->logo,
                'rating' => $product->store->rating,
            ],
            'attributes' => $attributes,
            'reviews' => $reviews,
            'related_products' => $relatedProducts,
        ];
    }

    public function searchProducts(Request $request)
    {
        $lang = $request->header('lang', 'en');
        $search = $request->input('name');

        $products = Product::where('name_en', 'LIKE', '%' . $search . '%')
            ->orWhere('name_ar', 'LIKE', '%' . $search . '%')
            ->orWhere('description_en', 'LIKE', '%' . $search . '%')
            ->orWhere('description_ar', 'LIKE', '%' . $search . '%')

            ->get();

        return $products->map(function ($product) use ($lang) {
            return [
                'id' => $product->id,
                'name' => $product->{"name_$lang"},
                'description' => $product->{"description_$lang"},
                'details' => $product->{"details_$lang"},
                'price' => $product->price,
                'discount' => $product->discount,
                'rate' => $product->rate,
                'stock' => $product->stock,
                'unit' => $product->{"unit_$lang"},
                'primary_image' => $product->images->first() ? asset('storage/' . $product->images->first()->url_image) : null,
            ];
        });
    }


    public function addReview($productId, array $data)
    {
        $user = Auth::user();
        if (!$user) {
            abort(401, 'Unauthorized');
        }

        $product = Product::findOrFail($productId);

        $review = $product->reviews()->create([
            'user_id' => $user->id,
            'rating' => $data['rating'],
            'review' => $data['review'],
        ]);

        $this->updateProductRating($product);

        return $review;
    }

    public function getReviews($productId)
    {
        $product = Product::with('reviews.user')->findOrFail($productId);
        return $product->reviews;
    }

    protected function updateProductRating(Product $product)
    {
        $ratingAggregate = $product->reviews()
            ->selectRaw('AVG(rating) as avg_rating, COUNT(rating) as count_rating')
            ->first();

        $product->rate = round($ratingAggregate->avg_rating, 2);
        $product->num_of_rates = $ratingAggregate->count_rating;
        $product->save();
    }

//    public function createProduct(array $data, $images = [], $imagesMeta = [])
//    {
//        $user = Auth::user();
//
//        if ($user->role !== 'seller') {
//            abort(403, 'Unauthorized');
//        }
//
//        // إنشاء المنتج
//        $product = Product::create(Arr::except($data, ['attributes', 'images']));
//
//        // حفظ السمات والقيم إن وجدت
//        $this->syncAttributes($product, $data['attributes'] ?? []);

        // حفظ الصور
//        $this->saveImages($product, $images, $imagesMeta);
//
//        return $product->load(['images', 'attributeValues']);
//    }

    public function updateProduct($id, array $data, $images = [], $imagesMeta = [])
    {
        $user = Auth::user();

        if ($user->role !== 'seller') {
            abort(403, 'Unauthorized');
        }

        $product = Product::findOrFail($id);
        $product->update(Arr::except($data, ['attributes', 'images']));

        // إزالة السمات السابقة ثم إعادة الربط
        $product->attributeValues()->detach();
        $this->syncAttributes($product, $data['attributes'] ?? []);

        // إضافة صور جديدة (اختياريًا يمكن حذف القديم إن أردت)
        $this->saveImages($product, $images, $imagesMeta);

        return $product->load(['images', 'attributeValues']);
    }

    public function deleteProduct($id)

      {
          $user = Auth::user();
          if ($user->role !== 'seller') {
              return response()->json(['message' => 'Unauthorized'], 403);
          }
          $product = Product::findOrFail($id);
          $product->images()->delete();
          $product->attributes()->delete();
          $product->delete();

          return response()->json(['message' => ' deleted']);
      }
    public function createProduct(array $data)
    {
        $user = Auth::user();
        if ($user->role !== 'seller') {
            abort(403, 'Unauthorized');
        }

        $product = Product::create(Arr::except($data, ['attributes', 'images']));

        $this->syncAttributes($product, $data['attributes'] ?? []);

        $this->saveImages(
            $product,
            $data['images'] ?? [],
            $data['images_meta'] ?? []
        );

        return $product;
    }

    private function syncAttributes(Product $product, array $attributes)
    {
        $user = Auth::user();

        foreach ($attributes as $attr) {
            $attributeId = $attr['attribute_id'] ?? null;

            if (!$attributeId || !Attribute::find($attributeId)) {
                continue;
            }

            foreach ($attr['values'] as $valueData) {
                $valueId = $valueData['value_id'] ?? null;

                if (!$valueId && isset($valueData['value_en'], $valueData['value_ar'])) {
                    $value = AttributeValue::create([
                        'attribute_id' => $attributeId,
                        'value_en' => $valueData['value_en'],
                        'value_ar' => $valueData['value_ar'],
                        'status' => 'pending',
                        'suggested_by' => $user->id,
                    ]);
                    $valueId = $value->id;
                }

                if ($valueId) {
                    $product->attributeValues()->attach($valueId, [
                        'price_impact' => $valueData['price_impact'] ?? 0,
                        'quantity' => $valueData['quantity'] ?? 0,
                    ]);
                }
            }
        }
    }

    private function saveImages(Product $product, $images, $imagesMeta)
    {
        if (!$images || !is_array($images)) return;

        foreach ($images as $index => $file) {
            if (!$file instanceof \Illuminate\Http\UploadedFile) {
                continue;
            }

            $path = $file->store('products', 'public');

            $meta = $imagesMeta[$index] ?? [];

            ImageProduct::create([
                'product_id'          => $product->id,
                'url_image'           => $path,
                'is_primary'          => $meta['is_primary'] ?? false,
                'attribute_value_id'  => $meta['attribute_value_id'] ?? null,
            ]);
        }
    }


    public function demandFeature($id)
    {
        $user = Auth::user();
        if ($user->role !== 'seller') {
            abort(403, 'Unauthorized');
        }

        $product = Product::findOrFail($id);
        $product->status = 'requested';
        $product->save();

        return true;
    }


    public function addImage($productId, $file, $isPrimary = false, $attributeValueId = null)
    {
        $user = Auth::user();
        $product = Product::findOrFail($productId);

//        if ($user->role === 'seller' && $product->user_id !== $user->id) {
//            abort(403, 'Unauthorized action.');
//        }

        if ($isPrimary) {
            $product->images()->update(['is_primary' => false]);
        }

        $imagePath = $file->store('products', 'public');

        $image = $product->images()->create([
            'url_image' => $imagePath,
            'is_primary' => $isPrimary,
            'attribute_value_id' => $attributeValueId,
        ]);

        return $image;
    }
    public function deleteImage($imageId)
    {
        $image = ImageProduct::findOrFail($imageId);

        // تأكد من المالك
//        $user = Auth::user();
//        if ($user->role === 'seller' && $image->product->user_id !== $user->id) {
//            abort(403, 'Unauthorized action.');
//        }

        // حذف من التخزين
        if (Storage::disk('public')->exists($image->url_image)) {
            Storage::disk('public')->delete($image->url_image);
        }

        return $image->delete();
    }


    public function getImages($productId)
    {
        $product = Product::with('images')->findOrFail($productId);
        return $product->images;
    }

    public function adminIndex(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $lang = $request->header('lang', 'en');

        $products = Product::withTrashed()
            ->with([
                'category',
                'store',
                'images',
                'attributes',
                'reviews.user'
            ])
            ->paginate(20);

        $formatted = $products->getCollection()->map(function ($product) use ($lang) {


            return [
                'id' => $product->id,
                'name' => $product->{"name_$lang"},
                'description' => $product->{"description_$lang"},
//                'details' => $product->{"details_$lang"},
                'price' => $product->price,
                'discount' => $product->discount,
                'rate' => $product->rate,
                'num_of_purchase' => $product->num_of_purchase,
                'stock' => $product->stock,
                'unit' => $product->{"unit_$lang"},
                'is_featured' => $product->is_featured,
                'status' => $product->status,
                'deleted_at' => $product->deleted_at,
                'category' => [
                    'id' => $product->category?->id,
                    'name' => $product->category?->{"name_$lang"},
                ],
                'store' => [
                    'id' => $product->store?->id,
                    'name' => $product->store?->{"name_$lang"},
                    'logo' => $product->store?->logo,
                    'rating' => $product->store?->rating,
                ],
                'attributes' => optional($product->attributes)->map(function ($attribute) use ($lang) {
                    return [
                        'id' => $attribute->id,
                        'name' => $attribute->{"name_$lang"},
                        'values' => optional($attribute->attributeValues)->map(fn($val) => $val->{"value_$lang"}),
                    ];
                }),

                'reviews' => optional($product->reviews)->map(function ($review) {
                    return [
                        'user' => $review->user->name ?? 'Unknown',
                        'rating' => $review->rating,
                        'review' => $review->review,
                        'created_at' => $review->created_at->toDateTimeString(),
                    ];
                }),

                'images' => $product->images->pluck('url_image'),
                'primary_image' => $product->images->first()?->url_image,
            ];
        });

        return [
            'products' => $formatted,
            'pagination' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
            ],
        ];
    }



    public function approveFeature($id)
    {
        $user = Auth::user();
        if ($user->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $product = Product::findOrFail($id);
        $product->is_featured = true;
        $product->status = 'active';
        $product->save();

        return true;
    }

    public function blockProduct($id)
    {
        $user = Auth::user();
        if ($user->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $product = Product::findOrFail($id);
        $product->status = 'inactive';
        $product->save();
        $product->delete();
        return true;
    }
    public function unblockProduct($id)
    {
        $user = Auth::user();
        if ($user->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $product = Product::withTrashed()->findOrFail($id);
        $product->status = 'active';
        $product->save();

        if ($product->trashed()) {
            $product->restore();  // يرجع المنتج إذا كان محذوف بنظام soft delete
        }

        return true;
    }



}
