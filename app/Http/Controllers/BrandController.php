<?php

namespace App\Http\Controllers;

use App\Http\Resources\BrandResource;
use App\Models\Brand;
use Illuminate\Http\Request;
use App\Services\BrandService;
use Illuminate\Support\Facades\App;

class BrandController extends Controller
{
    protected BrandService $brandService;
    public function __construct(BrandService $brandService)
    {
        $this->brandService = $brandService;
    }

    public function indexAdmin(Request $request)
    {

            $data = $this->brandService->getForAdmin(
                search: $request->get('search'),
                isActive: $request->boolean('is_active', null),
                page: $request->get('page', 1),
                perPage: $request->get('per_page', 15)
            );
            $brands = BrandResource::collection($data)->resolve();

            return response()->json([
                'brands' => $brands,
                'meta' => $this->paginationMeta($data)
            ]);
    }

    public function indexUser(Request $request)
    {
            $brands = $this->brandService->getForUser(
                search: $request->get('search')
            );

            $lang = $request->header('Accept-Language', 'en');
            $lang = in_array($lang, ['ar', 'en']) ? $lang : 'en';

            $brands = $brands->map(function ($brand) use ($lang) {
                return [
                    'id' => $brand->id,
                    'name' => $lang === 'ar' ? $brand->name_ar : $brand->name_en,
                    'image' => asset('storage/'.$brand->image),
                ];
            });

            return response()->json([
                'brands' => $brands
            ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name_ar' => 'required|string',
            'name_en' => 'required|string',
            'image'   => 'required|image:jpeg,jpg,png',
            'is_active' => 'boolean',
        ]);

        try {
            $brand = $this->brandService->create($data);
            return response()->json([
                'message' => 'Brand created successfully',
                'brand' => new BrandResource($brand),
            ]);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Failed to update brand'], 400);
        }
    }

    public function update(Request $request, Brand $brand)
    {
        $data = $request->validate([
            'name_ar' => 'sometimes|required|string',
            'name_en' => 'sometimes|required|string',
            'image'   => 'nullable|image:mimes:jpg,jpeg,png',
            'is_active' => 'sometimes|boolean',
        ]);

        try {
            $updatedBrand = $this->brandService->update($brand, $data);
            return response()->json([
                'message' => 'Brand updated successfully',
                'brand' => new BrandResource($updatedBrand),
            ]);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Failed to update brand'], 400);
        }
    }

    public function destroy(Brand $brand)
    {
        try {
            $this->brandService->delete($brand);
            return response()->json(['message' => 'Brand deleted successfully']);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Failed to delete brand'], 400);
        }
    }

    public function toggleStatus(Brand $brand)
    {
        try {
            $updated = $this->brandService->toggleStatus($brand);
            return response()->json([
                'brand' => new BrandResource($updated),
                'message' => 'Status toggled successfully'
            ]);
        } catch (\Throwable $e) {
            return response()->json(['message' => 'Failed to toggle status'], 400);
        }
    }

    public function show(Brand $brand)
    {
        return response()->json([
            'brand' => new BrandResource($brand),
        ]);
    }

    private function paginationMeta($paginator)
    {
        return [
            'current_page' => $paginator->currentPage(),
            'last_page' => $paginator->lastPage(),
            'per_page' => $paginator->perPage(),
            'total' => $paginator->total(),
        ];
    }
}
