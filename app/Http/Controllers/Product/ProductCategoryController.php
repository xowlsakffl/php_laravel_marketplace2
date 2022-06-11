<?php

namespace App\Http\Controllers\Product;

use App\Category;
use App\Product;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;

class ProductCategoryController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Product $product)
    {
        $categories = $product->categories()->get();

        return $this->showAll($categories);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product, Category $category)
    {
        //관계 연결
        //$product->categories()->attach([$category->id]);

        //관계 동기화
        //$product->categories()->sync([$category->id]);

        //관계 동기화 기존꺼 제거 x
        $product->categories()->syncWithoutDetaching([$category->id]);

        return $this->showAll($product->categories);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product, Category $category)
    {
        if(!$product->categories()->find($category->id)){
            return $this->errorResponse('The specified category is not a category of this product.', 404);
        }

        $product->categories()->detach($category->id);

        return $this->showAll($product->categories);
    }
}
