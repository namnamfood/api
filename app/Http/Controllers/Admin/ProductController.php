<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ImageMaker;
use App\Helpers\JSON;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // -- define required parameters
        $rules = ['branch_id' => 'required', 'category_id' => 'required'];
        // -- Validate and display error messages
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return JSON::response(true, 'Error occurred', $validator->errors()->all(), 400);
        }
        $branch_id = $request->get('branch_id');
        $category_id = $request->get('category_id');
        $brand_id = $request->get('brand_id');
        $products = Product::where('branch_id', $branch_id)
            ->where('category_id', $category_id)
            ->get();

        if ($brand_id) {
            $products = Product::where('branch_id', $branch_id)
                ->where('category_id', $category_id)
                ->where('brand_id', $brand_id)
                ->get();
            return JSON::response(false, 'available products', $products, 200);
        }
        return JSON::response(false, 'available products', $products, 200);
    }


    /**
     * Add new product.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // -- define required parameters
        $rules = [
            'name' => 'required',
            'price' => 'required|regex:/^\d*(\.\d{1,2})?$/',
            'quantity' => 'required',
            'branch_id' => 'required',
            'brand_id' => 'required',
            'category_id' => 'required',
            'cover' => 'required|image|max:1000',
        ];
        // -- Validate and display error messages
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return JSON::response(true, 'Error occurred', $validator->errors()->all(), 400);
        }
        $data = $request->only(['name', 'price', 'quantity', 'branch_id', 'brand_id', 'category_id', 'cover']);
        if ($request->file('cover')) {
            $file = $request->file('cover');
            $extension = $file->guessClientExtension();
            // Create unique name
            $fileName = time() . uniqid() . '.' . $extension;
            // upload image to the products folder in the uploads driver
            ImageMaker::upload($file, 'products', $fileName);
            // change the cover value
            $data['cover'] = $fileName;
        }
        // finally we create new branch
        $product = Product::create($data);
        return JSON::response(false, 'New product is created!', $product, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::find($id);
        return JSON::response(false, 'Product detail', $product, 200);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->only(['name', 'price', 'quantity', 'branch_id', 'brand_id', 'category_id', 'cover']);

        $product = Product::find($id);
        if ($request->file('cover')) {

            $file = $request->file('cover');
            // first delete old image before updating it
            $location = 'products';
            $exists = Storage::disk('uploads')->exists($location . '/' . $product->cover);
            if ($exists) {
                Storage::disk('uploads')->delete($location . '/' . $product->cover);
            }
            $extension = $file->guessClientExtension();
            // Create unique name
            $fileName = time() . uniqid() . '.' . $extension;
            // upload image to the related folder in the uploads driver
            ImageMaker::upload($file, $location, $fileName);
            // change the cover value
            $data['cover'] = $fileName;
        }
        // finally we update brand
        $product->update($data);
        return JSON::response(false, 'Product is updated!', $product, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Product::destroy($id);
        return JSON::response(false, 'Product is deleted', null, 200);
    }
}
