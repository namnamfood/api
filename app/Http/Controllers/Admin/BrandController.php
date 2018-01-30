<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ImageMaker;
use App\Helpers\JSON;
use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // -- define required parameters
        $rules = [
            'name' => 'required',
            'cover' => 'required|image|max:1000',
        ];
        // -- Validate and display error messages
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return JSON::response(true, 'Error occured', $validator->errors()->all(), 400);
        }
        $data = $request->only(['name', 'cover']);
        if ($request->file('cover')) {
            $file = $request->file('cover');
            $extension = $file->guessClientExtension();
            // Create unique name
            $fileName = time() . uniqid() . '.' . $extension;
            // upload image to the branches folder in the uploads driver
            ImageMaker::upload($file, 'brands', $fileName);
            // change the cover value
            $data['cover'] = $fileName;
        }
        // finally we create new branch
        $brand = Brand::create($data);
        return JSON::response(false, 'New brand is created!', $brand, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $brand = Brand::with('products')->find($id);
        return JSON::response(false, 'Brand Detail', $brand, 200);
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
        $data = $request->only(['name', 'cover']);
        $brand = Brand::find($id);

        if ($request->file('cover')) {

            $file = $request->file('cover');
            // first delete old image before updating it
            $location = 'brands';
            $exists = Storage::disk('uploads')->exists($location . '/' . $brand->cover);
            if ($exists) {
                Storage::disk('uploads')->delete($location . '/' . $brand->cover);
            }

            $extension = $file->guessClientExtension();
            // Create unique name
            $fileName = time() . uniqid() . '.' . $extension;
            // upload image to the related folder in the uploads driver
            ImageMaker::upload($file, 'brands', $fileName);
            // change the cover value
            $data['cover'] = $fileName;
        }
        // finally we update brand
        $brand->update($data);
        return JSON::response(false, 'Brand is updated!', $brand, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Brand::destroy($id);
        return JSON::response(false, 'Brand is deleted', null, 200);
    }
}
