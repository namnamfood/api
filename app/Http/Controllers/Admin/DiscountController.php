<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\JSON;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DiscountController extends Controller
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
        $products = Product::Discounted()->where('branch_id', $branch_id)
            ->where('category_id', $category_id)
            ->get();

        if ($brand_id) {
            $products = Product::Discounted()->where('branch_id', $branch_id)
                ->where('category_id', $category_id)
                ->where('brand_id', $brand_id)
                ->get();
            return JSON::response(false, 'Discounted products', $products, 200);
        }
        return JSON::response(false, 'Discounted products', $products, 200);
    }


    /**
     * create discount for product(S)
     * .
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = ['type' => 'required', 'discount' => 'required'];

        switch ($request->type) {
            case 'product':
                $rules['product_id'] = 'required';
                $validator = Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    return JSON::response(true, 'Error occurred', $validator->errors()->all(), 400);
                }
                $product_id = $request->product_id;
                $product = Product::find($product_id);
                $price = $product->price;
                // -- Calculate discounted price
                $discounted_price = getDiscount($price, $request->discount);
                $product->discounted_price = $discounted_price;
                $product->save();
                return JSON::response(false, 'Discount has done for product', $product, 200);
            case 'all':
                $products = Product::inStock()->get();
                foreach ($products as $product) {
                    $price = $product->price;
                    $discounted_price = getDiscount($price, $request->discount);
                    $product->discounted_price = $discounted_price;
                    $product->save();
                }
                return JSON::response(false, 'Discount has done for all product', $products, 200);
            case 'category':
                $rules['category_id'] = 'required';
                $validator = Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    return JSON::response(true, 'Error occurred', $validator->errors()->all(), 400);
                }
                $category = Category::find($request->category_id);
                // if category is parent category(have children)
                // then make discount its sub categories products
                if ($category->children()->count()) {
                    $products = array();
                    foreach ($category->children as $child) {
                        foreach ($child->products as $product) {
                            $price = $product->price;
                            $discounted_price = getDiscount($price, $request->discount);
                            $product->discounted_price = $discounted_price;
                            $product->save();
                            array_push($products, $product);
                        }

                    }
                    return JSON::response(false, 'Discount has done for parent category products', $products, 200);
                }

                $products = $category->products;
                foreach ($products as $product) {
                    $price = $product->price;
                    $discounted_price = getDiscount($price, $request->discount);
                    $product->discounted_price = $discounted_price;
                    $product->save();
                }

                return JSON::response(false, 'Discount has done for category products', $products, 200);
            default://not supported type
                return JSON::response(false, 'No such discount type', null, 404);

        }
    }
}
