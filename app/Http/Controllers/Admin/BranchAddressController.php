<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\JSON;
use App\Http\Controllers\Controller;
use App\Models\BranchAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BranchAddressController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $addresses = BranchAddress::with('street')->get();
        return JSON::response(false, 'all branch addresses', $addresses, 200);
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
            'branch_id' => 'required',
            'street_id' => 'required',
            'packet_price' => 'required',
        ];
        // -- Validate and display error messages
        $data = $request->only(['branch_id', 'street_id', 'packet_price']);
        $branchAddress = BranchAddress::where('branch_id', $data['branch_id'])->where('street_id', $data['street_id'])->first();
        if ($branchAddress) {
            return JSON::response(true, 'This address already exists on this branch', null, 400);
        }
        $validator = Validator::make($data, $rules);
        if ($validator->fails()) {
            return JSON::response(true, 'Error occured', $validator->errors()->all(), 400);
        }

        $newBranchAddress = BranchAddress::create($data);
        return JSON::response(false, 'New branch address is created!', $newBranchAddress, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

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
        $data = $request->only(['branch_id', 'street_id', 'packet_price']);

        $branchAddress = BranchAddress::find($id);
        if ($branchAddress->street_id == $data['street_id'] && $branchAddress->branch_id == $data['branch_id']) {
            return JSON::response(true, 'This address already exists on this branch', $branchAddress, 400);
        }
        $branchAddress->update($data);
        return JSON::response(false, 'Branch address is updated!', $branchAddress, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        BranchAddress::destroy($id);
        return JSON::response(false, 'Branch address is deleted!', null, 200);
    }
}
