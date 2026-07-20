<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::paginate(10);

        return CategoryResource::collection($categories);
    }

    public function store(StoreCategoryRequest $request)
    {
        $category = Category::create($request->validated());

        return response()->json([
            'status' => true,
            'message' => 'Category created successfully',
            'data' => new CategoryResource($category),
        ], 201);
    }

    public function show(Category $category)
    {
        return new CategoryResource($category);
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $category->update($request->validated());

        return response()->json([
            'status' => true,
            'message' => 'Category updated successfully',
            'data' => new CategoryResource($category),
        ]);
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return response()->json([
            'status' => true,
            'message' => 'Category deleted successfully',
        ]);
    }
}