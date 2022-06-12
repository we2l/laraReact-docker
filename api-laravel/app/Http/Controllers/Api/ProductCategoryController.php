<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductCategoryRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{
    private Product $product;
    private Category $category;

    public function __construct(Product $product, Category $category)
    {
        $this->product = $product;
        $this->category = $category;
    }

    public function index()
    {
        return $this->success($this->product::with('categories')->get());
    }

    public function store(StoreProductCategoryRequest $request)
    {
        $product = $this->product->where('slug', $request->validated()['slug-product'])->first();
        $categories = $request->validated()['slug-categories'];

        foreach($categories as $slug) {
            $category = $this->category->where('slug', $slug)->first();

            if(!$category)
                return $this->error(404);
            
            $product->categories()->saveMany([$category]);

            return $this->success(null,201);
        }

    }
}
