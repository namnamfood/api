<?php
/**
 * Created by PhpStorm.
 * User: alirzayev
 * Date: 23/01/2017
 * Time: 16:34
 */

namespace App\Http\Controllers;


use App\Gelsin\Models\Branch;
use App\Gelsin\Models\BranchAdress;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


class BranchAddressController extends Controller
{

    public function __construct()
    {

    }


    public function index(Request $request)
    {

        $addresses = BranchAdress::all();


        return new JsonResponse([
            "error" => false,
            "message" => "success",
            'branches' => $addresses,
        ]);
    }

    /**
     * @param $address_id
     * @return JsonResponse
     */
    public function showBranch($address_id)
    {

        if ($address_id) {

            $address = BranchAdress::find($address_id);
            $address->branch;

            $error = false;
            $message = "success";
        } else {
            $error = true;
            $message = "Mismatch parameter";
            $branch = null;
        }


        return new JsonResponse([
            "error" => $error,
            "message" => $message,
            "address" => $address->street_name,
            'branch' => $address->branch
        ]);
    }

    /**
     * @param $branch_id
     * @return JsonResponse
     */
    public function showBranchAddresses($branch_id)
    {

        if ($branch_id) {

            $branch = Branch::find($branch_id);
            if (!$branch) {
                return new JsonResponse([
                    "error" => true,
                    "message" => "no such branch exists",
                ]);
            }

            $branch->addresses;
            $error = false;
            $message = $branch->name . " addresses are listed below";

        } else {
            $error = true;
            $message = "Mismatch parameter";
            $branch = null;
        }


        return new JsonResponse([
            "error" => $error,
            "message" => $message,
            "addresses" => $branch->addresses,
        ]);
    }

    /**
     * Create  new category.
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request)
    {


        // -- First  Validate
        $this->validate($request, [
            'street_name' => 'required',
            'branch_id' => 'required',
        ]);

        // All good so create new branch address
        $address = BranchAdress::create($request->all());

        return new JsonResponse([
            "error" => false,
            'message' => 'success!',
            "category" => $address
        ]);

    }


    /**
     * Update  new category.
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request)
    {


        // All good so update branch
        $address = BranchAdress::find($request->get('address_id'));
        if ($request->get("street_name")) {

            $address->street_name = $request->get("street_name");
            $message = "Street name updated";
        }
        if ($request->get("street_line_extra")) {

            $address->street_line_extra = $request->get("street_line_extra");
            $message = "Street Line updated";
        }
        if ($request->get("branch_id")) {

            $address->branch_id = $request->get("branch_id");
            $message = "Street Line updated";
        }


        $address->save();

        return new JsonResponse([
            "error" => false,
            'message' => $message,
            "category" => $address
        ]);

    }


    /**
     * Delete   category.
     * @param Request $request
     * @return JsonResponse
     */
    public function delete(Request $request)
    {


        // All good so delete branch address and its relations
        $branch_address = BranchAdress::find($request->get('address_id'));
        $branch_address->delete();


        return new JsonResponse([
            "error" => false,
            'message' => $branch_address->name . " is soft deleted",
            "category" => $branch_address
        ]);

    }

}