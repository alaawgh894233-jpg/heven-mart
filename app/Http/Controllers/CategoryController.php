<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\UserCategoryResource;
use App\Models\Category;
use App\Services\CategoryService;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    protected $categoryService ;
    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }


    public function create(CreateCategoryRequest $request)
    {
        try {
            $category = $this->categoryService
                ->create($request->validated());
        }catch (\Exception $exception){
            return response()->json(['message' => $exception->getMessage()], 400);
        }
        return response()->json([
            'message' => 'Category created successfully',
            'category' => new CategoryResource($category)
        ]);
    }

    public function update(Category $category, UpdateCategoryRequest $request)
    {
        try {
            $category = $this->categoryService
                ->update($category, $request->validated());
        }catch (\Exception $e){
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
        return response()->json([
            'message' => 'Category edited successfully',
            'category' => $category
        ]);
    }

    public function toggleStatus(Request $request, Category $category)
    {
        $lang = $request->header('Accept-Language','en');
        $lang = in_array($lang, ['ar','en']) ? $lang : 'en';
        return response()->json([
            'category' => new CategoryResource($category)
        ]);

    }

    public function show(Category $category)
    {
        return response()->json([
            'category' => new CategoryResource($category)
        ]);
    }

    public function delete(Category $category)
    {
        try {
            $this->categoryService->delete($category);
        }catch (\Exception $e){
            return response()->json([
                'message' => $e->getMessage()
            ],400);
        }
        return response()->json([
            'message' => 'Category deleted successfully'
        ]);
    }

    public function getAvailableParentsForUpdateCategory(Category $category)
    {
        $validParents = $this->categoryService->getValidParentsFor($category);

        return response()->json([
            'categories' => CategoryResource::collection($validParents),
        ]);
    }


    public function getAllForAdmin(Request $request)
    {
        $per_page = $request->input('per_page', 5);
        $page = $request->input('page', 1);
        $data = $this->categoryService->getAllForAdmin($per_page, $page, $request->all());
        $categories = CategoryResource::collection($data)->resolve();
        return response()->json([
            'categories' => $categories,
            'meta'=>$this->paginationMeta($data)
        ]);
    }

    public function getLeafCategories(Request $request)
    {
        $lang = $request->header('Accept-Language');
        $lang= in_array($lang, ['ar', 'en'])? $lang : 'en';
        return response()->json([
            'categories' => $this->categoryService->getLeafCategories($lang)
        ]);
    }

    public function getParents(Request $request)
    {
        $lang = $request->header('Accept-Language', 'en');
        $lang= in_array($lang, ['ar', 'en'])? $lang : 'en';
        return response()->json([
            'categories' => $this->categoryService->getParents($lang),
        ]);
    }


    public function getchildren(Request $request, Category $category)
    {
        $lang = $request->header('Accept-Language', 'en');
        $lang= in_array($lang, ['ar', 'en'])? $lang : 'en';
        return response()->json([
            'categories' => $this->categoryService->getchildren($category, $lang),
        ]);
    }



    public function getSiblingLeafCategories(Request $request, Category $category)
    {
        $lang = $request->header('Accept-Language', 'en');
        $lang= in_array($lang, ['ar', 'en'])? $lang : 'en';
        $siblings = Category::where('parent_id', $category->parent_id)
            ->where('id', '!=', $category->id)
            ->whereDoesntHave('children') // فقط أوراق
            ->where('is_active', 'true')   // فقط نشطة
            ->get()->map(function ($item) use ($lang) {
                return [
                    'id' => $item->id,
                    'name' => $item->{'name_' . $lang},
                    'image' => asset($item->image),
                ];
            });
        return response()->json([
            'categories' => $siblings,
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
