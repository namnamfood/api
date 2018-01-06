<?php

namespace App\Http\Controllers;

use App\Models\Branch;

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
}
