<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{   
    private $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = $this->product::paginate();
    
        return $this->success(ProductResource::collection($products));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\StoreProductRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductRequest $request)
    {
        $product = $this->product::create($request->validated());

        return $this->success(new ProductResource($product));
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function show(string $slug)
    {
        $product = $this->product->where('slug', $slug)->first();
        
        if(!$product)
            return $this->error(404);

        return $this->success(new ProductResource($product));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Requests\UpdateProductRequest  $request
     * @param  string  $slug
     * @return \Illuminate\Http\UpdateProductRequest
     */
    public function update(UpdateProductRequest $request, string $slug)
    {   
        $product = $this->product::where('slug', $slug)->first();
        if(!$product)
            return $this->error(404);

        $product->update($request->validated());

        return $this->success();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string $slug
     * @return \Illuminate\Http\Response
     */
    public function destroy(string $slug)
    {
        $product = $this->product::where('slug', $slug);

        if(!$product)
            return $this->error(404);

        $product->delete();
        return $this->success();
    }
}
