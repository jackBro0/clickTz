<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $product = Product::with('category', 'user')->when(isset($request->name), function ($q) use ($request) {
            return $q->where('name', 'LIKE', "%{$request->name}%");
        })
            ->get();
        return $this->responseSuccess($product);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'price' => 'required',
            'user_id' => 'required',
            'category_id' => 'required',
            'photo' => 'required|mimes:jpg,bmp,png'
        ]);

        if ($validator->fails()) {
            return $this->responseFail(422, $validator->messages());
        }

        $photo = Storage::put('public/product/images', $request->photo,);
        $path = str_replace('public/product/images/', '', $photo);
        $product = Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'user_id' => $request->user_id,
            'category_id' => $request->category_id,
            'photo' => 'storage/product/images/' . $path,
        ]);

        return $this->responseSuccess($product);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return $this->responseFail(404, 'Product not found');
        }
        return $this->responseSuccess(Product::findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'price' => 'required',
            'category_id' => 'required'
        ]);


        if ($validator->fails()) {
            return $this->responseFail(422, $validator->messages());
        }

        $product = Product::find($id);

        if (!$product) {
            return $this->responseFail(404, 'Product not found');
        }

        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->category_id = $request->category_id;
        if (isset($request->photo)) {
            $photo = Storage::put('public/product/images', $request->photo,);
            $path = str_replace('public/product/images/', '', $photo);
            $product->photo = $path;
        }
        $product->update();

        return $this->responseSuccess($product);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return $this->responseFail(404, 'Product not found');
        }
        $product->delete();
        return $this->responseDelete('Product successfully deleted');
    }
}
