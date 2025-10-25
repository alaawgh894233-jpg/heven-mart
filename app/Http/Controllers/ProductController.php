<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddAttributeRequest;
use App\Http\Requests\AddAttributeValueRequest;
use App\Http\Requests\AddImageRequest;
use App\Http\Requests\AddReviewRequest;
use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Attribute;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    protected  $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }


    public function index(Request $request)
    {
        $result = $this->productService->getProducts($request);
        return response()->json($result);
    }


    public function show($id, Request $request)
    {
        $product = $this->productService->getProductById($id, $request);
        return response()->json($product);
    }


    public function search(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
        ]);

        $products = $this->productService->searchProducts($request);

        if ($products->isEmpty()) {
            return response()->json(['message' => 'product does not exists!'], 404);
        }

        return response()->json($products, 200);
    }



    public function addReview(AddReviewRequest $request, $productId)
    {
        $review = $this->productService->addReview($productId, $request->validated());
        return response()->json($review, 201);
    }


    public function getReviews($productId)
    {
        $reviews = $this->productService->getReviews($productId);
        return response()->json($reviews);
    }


    public function store(CreateProductRequest $request)
    {
        $product = $this->productService->createProduct($request->validated());
        return response()->json($product, 201);
    }


    public function update(UpdateProductRequest $request, $id)
    {
        $product = $this->productService->updateProduct($id, $request->validated());
        return response()->json($product);
    }


    public function destroy($id)
    {
        $this->productService->deleteProduct($id);
        return response()->json(['message' => 'Product deleted']);
    }


    public function demandFeature($id)
    {
        $this->productService->demandFeature($id);
        return response()->json(['message' => 'Feature request sent']);
    }


//    public function addAttribute(AddAttributeRequest $request, $productId)
//    {
//        $attribute = $this->productService->addAttribute($productId, $request->validated());
//        return response()->json($attribute, 201);
//    }
//
//
//    public function addAttributeValue(AddAttributeValueRequest $request)
//    {
//        $value = $this->productService->addAttributeValue($request->validated());
//        return response()->json($value, 201);
//    }
//

    public function addImage(AddImageRequest $request, $productId)
    {
        $file = $request->file('url_image');
        $isPrimary = $request->input('is_primary', false);

        $image = $this->productService->addImage($productId, $file, $isPrimary);
        return response()->json($image, 201);
    }


    public function images($productId)
    {
        $images = $this->productService->getImages($productId);
        return response()->json($images);
    }


    public function adminIndex(Request $request)
    {
        $products = $this->productService->adminIndex($request);
        return response()->json($products);
    }


    public function approveFeature($id)
    {
        $this->productService->approveFeature($id);
        return response()->json(['message' => 'Feature approved']);
    }


    public function block($id)
    {
        $this->productService->blockProduct($id);
        return response()->json(['message' => 'Product blocked']);
    }
    public function unblock($id)
    {
        $this->productService->unblockProduct($id);
        return response()->json(['message' => 'Product unblocked']);
    }


    public function assignAttributeValueToProduct($productId, $attributeId, $valueId)
    {
        $user = Auth::user();

        if (!$user || $user->role !== 'seller') {
            abort(403);
        }
        $product = Product::where('user_id', $user->id)->findOrFail($productId);
        $attribute = Attribute::findOrFail($attributeId);

        $value = $attribute->values()->where('id', $valueId)->first();
        if (!$value) {
            abort(422, 'Invalid value selected for this attribute.');
        }

        $product->attributeValues()->attach($value->id, ['attribute_id' => $attributeId]);

        return response()->json(['message' => 'Attribute value assigned to product']);
    }

    public function getAttributeValues($attributeId)
    {
        $attribute = Attribute::with('values')->findOrFail($attributeId);

        return response()->json([
            'success' => true,
            'attribute_id' => $attribute->id,
            'values' => $attribute->values
        ]);
    }


}
