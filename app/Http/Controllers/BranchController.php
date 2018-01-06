<?php

namespace App\Http\Controllers;

use App\Helpers\FilterBy;
use App\Helpers\JSON;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BranchController extends Controller
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
        $rules = [
            'lat' => 'required',
            'lng' => 'required',
        ];
        // -- Validate and display error messages
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return JSON::response(true, 'Error occured', $validator->errors()->all(), 400);
        }
        $lat = $request->lat;
        $lng = $request->lng;
        $branches = Branch::all();

        foreach ($branches as $branch) {
            $distance = FilterBy::distance($lat, $lng, $branch->latitude, $branch->longitude);
            $branch->distance = $distance;
        }
        // sort by distance
        $branches = collect($branches)->sortBy('distance')->values()->all();
        return JSON::response(false, 'All available branches', $branches, 200);
    }

    public function nearMeBranches(Request $request)
    {
        // -- define required parameters
        $rules = [
            'radius' => 'required',
            'lat' => 'required',
            'lng' => 'required',
        ];
        // -- Validate and display error messages
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return JSON::response(true, 'Error occured', $validator->errors()->all(), 400);
        }

        $radius = 4; // default value
        $lat = $request->lat;
        $lng = $request->lng;
        $radius = $request->radius;
        $branches = Branch::all();
        $nearMeBranches = [];
        foreach ($branches as $branch) {
            $distance = FilterBy::distance($lat, $lng, $branch->latitude, $branch->longitude);
            $branch->distance = $distance;
            if ($radius >= $branch->distance) {
                array_push($nearMeBranches, $branch);
            }
        }

        // sort by distance
        $nearMeBranches = collect($nearMeBranches)->sortBy('distance')->values()->all();
        return JSON::response(false, 'All available branches', $nearMeBranches, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $branch = Branch::with('addresses.street')->find($id);
        return JSON::response(false, 'Related branch', $branch, 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
