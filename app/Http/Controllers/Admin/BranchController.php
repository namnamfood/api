<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ImageMaker;
use App\Helpers\JSON;
use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $branches = Branch::all();
        return JSON::response(false, 'All available branches', $branches, 200);
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
            'latitude' => 'required',
            'longitude' => 'required',
            'region_id' => 'required',
            'cover' => 'image|max:1000',
        ];
        // -- Validate and display error messages
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return JSON::response(true, 'Error occured', $validator->errors()->all(), 400);
        }
        $data = $request->only(['name', 'region_id', 'description', 'cover', 'latitude', 'longitude', 'open_time', 'close_time']);
        if ($request->file('cover')) {
            $file = $request->file('cover');
            $extension = $file->guessClientExtension();
            // Create unique name
            $fileName = time() . uniqid() . '.' . $extension;
            // upload image to the branches folder in the uploads driver
            ImageMaker::upload($file, 'branches', $fileName);
            // change the cover value
            $data['cover'] = $fileName;
        }
        // finally we create new branch
        $branch = Branch::create($data);
        return JSON::response(false, 'New branch is created!', $branch, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $branch = Branch::find($id);
        return JSON::response(false, 'Related branch', $branch, 200);
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
        $data = $request->only(['name', 'region_id', 'description', 'cover', 'latitude', 'longitude', 'open_time', 'close_time']);
        $branch = Branch::find($id);
        if ($request->file('cover')) {

            $file = $request->file('cover');
            // first delete old image
            $location = 'branches';
            $exists = Storage::disk('uploads')->exists($location . '/' . $branch->cover);
            if ($exists) {
                Storage::disk('uploads')->delete($location . '/' . $branch->cover);
            }
            $extension = $file->guessClientExtension();
            // Create unique name
            $fileName = time() . uniqid() . '.' . $extension;
            // upload image to the branches folder in the uploads driver
            ImageMaker::upload($file, $location, $fileName);
            // change the cover value
            $data['cover'] = $fileName;
        }

        $branch->update($data);
        return JSON::response(false, 'Branch is updated!', $branch, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $branch = Branch::destroy($id);
        return JSON::response(false, 'Branch is deleted!', $branch, 200);
    }
}
