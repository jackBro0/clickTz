<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->responseSuccess(Category::get());
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->responseFail(422, $validator->messages());
        }

        $category = Category::create([
            'name' => $request->name
        ]);

        return $this->responseSuccess($category);
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Category $category
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return $this->responseFail(404, 'Category not found');
        }
        return $this->responseSuccess(Category::findOrFail($id));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Category $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ]);


        if ($validator->fails()) {
            return $this->responseFail(422, $validator->messages());
        }

        $category = Category::find($id);

        if (!$category) {
            return $this->responseFail(404, 'Category not found');
        }
        $category->name = $request->name;
        $category->update();

        return $this->responseSuccess($category);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Category $category
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return $this->responseFail(404, 'Category not found');
        }
        $category->delete();
        return $this->responseDelete('Category successfully deleted');
    }
}
