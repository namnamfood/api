<?php

namespace App\Http\Controllers;

use App\Helpers\JSON;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::parents();
        return JSON::response(false, 'All available categories', $categories, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = Category::with('children')->find($id);
        return JSON::response(false, 'Category details', $category, 200);
    }

}
