<?php

namespace App\Http\Controllers\VendorManager;

use App\Http\Controllers\Controller;
use App\Models\CampaignAssignVendor;
use App\Models\VendorLead;
use App\Repository\CampaignAssignRepository\VendorManagerRepository\VendorManagerRepository as CAVendorManagerRepository;
use App\Repository\CampaignAssignRepository\VendorRepository\VendorRepository as CAVendorRepository;
use App\Repository\VendorLead\VendorLeadRepository;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    private $data;
    /**
     * @var CAVendorRepository
     */
    private $CAVendorRepository;
    /**
     * @var CAVendorManagerRepository
     */
    private $CAVendorManagerRepository;
    /**
     * @var VendorLeadRepository
     */
    private $vendorLeadRepository;

    public function __construct(
        CAVendorManagerRepository $CAVendorManagerRepository,
        CAVendorRepository $CAVendorRepository,
        VendorLeadRepository $vendorLeadRepository
    )
    {
        $this->data = array();
        $this->CAVendorRepository = $CAVendorRepository;
        $this->CAVendorManagerRepository = $CAVendorManagerRepository;
        $this->vendorLeadRepository = $vendorLeadRepository;
    }

    public function index($cavm_id)
    {
        $this->data['resultCAVM'] = $this->CAVendorManagerRepository->find(base64_decode($cavm_id));
        //dd($this->data['resultCAVM']->vendors->toArray());
        return view('vendor_manager.lead.list', $this->data);
    }

    public function uploadLeads(Request $request)
    {
        $attributes = $request->all();
        if(isset($attributes['lead_file']) && !empty($attributes['lead_file'])) {
            $response = $this->vendorLeadRepository->uploadLeads($attributes);
            if($response['status'] == TRUE) {
                return response()->json(array('status' => true, 'message' => $response['message']));
            } else {
                return response()->json(array('status' => false, 'message' => $response['message']));
            }
        } else {
            return response()->json(array('status' => false, 'message' => 'Please upload file'));
        }
    }

    public function getVendorLeads(Request $request): \Illuminate\Http\JsonResponse
    {
        $filters = array_filter(json_decode($request->get('filters'), true));
        $search_data = $request->get('search');
        $searchValue = $search_data['value'];
        $order = $request->get('order');
        $draw = $request->get('draw');
        $limit = $request->get("length"); // Rows display per page
        $offset = $request->get("start");

        $query = VendorLead::query();
        $query->where('status', 1);

        if($request->has('cavm_id')) {
            $resultCAVendors = CampaignAssignVendor::where('campaign_assign_vm_id', base64_decode($request->get('cavm_id')))->get();
            $query->whereIn('ca_vendor_id', $resultCAVendors->pluck('id')->toArray());
        }

        $query->with('vendor');

        $totalRecords = $query->count();

        //Search Data
        if(isset($searchValue) && $searchValue != "") {
            $query->where("name", "like", "%$searchValue%");
            $query->orWhere("email_address", "like", "%$searchValue%");
        }
        //Filters
        if(!empty($filters)) {

        }


        //Order By
        $orderColumn = null;
        if ($request->has('order')){
            $order = $request->get('order');
            $orderColumn = $order[0]['column'];
            $orderDirection = $order[0]['dir'];
        }

        switch ($orderColumn) {
            case '0': $query->orderBy('first_name', $orderDirection); break;
            case '1': $query->orderBy('first_name', $orderDirection); break;
            case '2': $query->orderBy('first_name', $orderDirection); break;
            case '3': $query->orderBy('first_name', $orderDirection); break;
            case '4': $query->orderBy('first_name', $orderDirection); break;
            default: $query->orderBy('first_name'); break;
        }

        $totalFilterRecords = $query->count();
        if($limit > 0) {
            $query->offset($offset);
            $query->limit($limit);
        }

        $result = $query->get();

        //dd($result->toArray());

        $ajaxData = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalFilterRecords,
            "aaData" => $result
        );

        return response()->json($ajaxData);
    }
}
