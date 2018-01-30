<?php

namespace App\Http\Controllers;

use App\Helpers\JSON;
use App\Models\Product;
use Illuminate\Http\Request;
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
}
