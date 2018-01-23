<?php

namespace App\Http\Controllers;

use App\Helpers\JSON;
use App\Models\Brand;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $brands = Brand::all();
        return JSON::response(false, 'All available brands', $brands, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $brand = Brand::find($id);
        return JSON::response(false, 'Brand Detail', $brand, 200);
    }
}
