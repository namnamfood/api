<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ImageMaker;
use App\Helpers\JSON;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
        $categories = Category::parents();
        return JSON::response(false, 'All available categories', $categories, 200);
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
            'parent_id' => 'required',
            'cover' => 'required|image|max:1000',
        ];
        // -- Validate and display error messages
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return JSON::response(true, 'Error occured', $validator->errors()->all(), 400);
        }
        $data = $request->only(['name', 'parent_id', 'cover']);
        if ($request->file('cover')) {
            $file = $request->file('cover');
            $extension = $file->guessClientExtension();
            // Create unique name
            $fileName = time() . uniqid() . '.' . $extension;
            // upload image to the branches folder in the uploads driver
            ImageMaker::upload($file, 'categories', $fileName);
            // change the cover value
            $data['cover'] = $fileName;
        }
        // finally we create new branch
        $category = Category::create($data);
        return JSON::response(false, 'New category is created!', $category, 200);
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->only(['name', 'parent_id', 'cover']);
        $category = Category::find($id);

        if ($request->file('cover')) {

            $file = $request->file('cover');
            // first delete old image before updating it
            $location = 'categories';
            $exists = Storage::disk('uploads')->exists($location . '/' . $category->cover);
            if ($exists) {
                Storage::disk('uploads')->delete($location . '/' . $category->cover);
            }

            $extension = $file->guessClientExtension();
            // Create unique name
            $fileName = time() . uniqid() . '.' . $extension;
            // upload image to the related folder in the uploads driver
            ImageMaker::upload($file, 'categories', $fileName);
            // change the cover value
            $data['cover'] = $fileName;
        }
        // finally we update category
        $category->update($data);
        return JSON::response(false, 'Category is updated!', $category, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::destroy($id);
        return JSON::response(false, 'Category is deleted!', null, 200);
    }
}
