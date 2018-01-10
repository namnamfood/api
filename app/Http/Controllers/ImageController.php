<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Brand;
use App\Models\Category;

class ImageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param $branch_id
     * @return \Illuminate\Http\Response
     */
    public function branchImage($branch_id)
    {
        // All good so show brand image
        $branch = Branch::find($branch_id);
        if (!$branch->cover) {
            $path = storage_path('app/static/no_image.png');
            $image = response()->download($path, $branch->cover);
            return $image;
        }
        // Now it is time to display image
        $path = storage_path('app/uploads/branches/' . $branch->cover);
        $image = response()->download($path, $branch->cover);
        return $image;
    }

    /**
     * Display the category cover.
     *
     * @param $category_id
     * @return \Illuminate\Http\Response
     */
    public function categoryImage($category_id)
    {
        // All good so show brand image
        $category = Category::find($category_id);
        if (!$category->cover) {
            $path = storage_path('app/static/no_image.png');
            $image = response()->download($path, $category->cover);
            return $image;
        }
        // Now it is time to display image
        $path = storage_path('app/uploads/categories/' . $category->cover);
        $image = response()->download($path, $category->cover);
        return $image;
    }

    /**
     * Display the brand cover.
     *
     * @param $category_id
     * @return \Illuminate\Http\Response
     */
    public function brandImage($brand_id)
    {
        // All good so show brand image
        $brand = Brand::find($brand_id);
        if (!$brand->cover) {
            $path = storage_path('app/static/no_image.png');
            $image = response()->download($path, $brand->cover);
            return $image;
        }
        // Now it is time to display image
        $path = storage_path('app/uploads/brands/' . $brand->cover);
        $image = response()->download($path, $brand->cover);
        return $image;
    }
}
