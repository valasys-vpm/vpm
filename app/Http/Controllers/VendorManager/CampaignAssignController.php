<?php

namespace App\Http\Controllers\VendorManager;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\CampaignAssignRATL;
use App\Models\CampaignAssignVendorManager;
use App\Repository\CampaignAssignRepository\CampaignAssignRepository;
use App\Repository\CampaignAssignRepository\VendorRepository\VendorRepository as CAVendorRepository;
use App\Repository\CampaignRepository\CampaignRepository;
use App\Repository\VendorRepository\VendorRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CampaignAssignController extends Controller
{
    private $data;
    private $campaignAssignRepository;
    private $CAVendorRepository;
    private $vendorRepository;
    private $campaignRepository;

    public function __construct(
        CampaignAssignRepository $campaignAssignRepository,
        CAVendorRepository $CAVendorRepository,
        VendorRepository $vendorRepository,
        CampaignRepository $campaignRepository
    )
    {
        $this->data = array();
        $this->campaignAssignRepository = $campaignAssignRepository;
        $this->CAVendorRepository = $CAVendorRepository;
        $this->vendorRepository = $vendorRepository;
        $this->campaignRepository =$campaignRepository;
    }

    public function index()
    {
        $this->data['resultCampaigns'] = $this->campaignAssignRepository->getCampaignToAssignForVM(Auth::id());
        $this->data['resultVendors'] = $this->vendorRepository->get(array('status' => 1));
        //dd($this->data['resultCampaigns']->toArray());
        return view('vendor_manager.campaign_assign.list', $this->data);
    }
    public function show($id)
    {

        try {

            $this->data['resultCampaign'] = $this->campaignRepository->find(base64_decode($id));

            return view('vendor_manager.campaign_assign.show', $this->data);
        } catch (\Exception $exception) {
            return redirect()->route('vendor_manager.campaign_assign.list')->with('error', ['title' => 'Error while processing request', 'message' => 'Campaign details not found']);
        }
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $attributes = $request->all();
        $response = $this->CAVendorRepository->store($attributes);
        if($response['status'] == TRUE) {
            return redirect()->route('vendor_manager.campaign_assign.list')->with('success', ['title' => 'Successful', 'message' => $response['message']]);
        } else {
            return back()->withInput()->with('error', ['title' => 'Error while processing request', 'message' => $response['message']]);
        }
    }
    public function getAssignedCampaigns(Request $request): \Illuminate\Http\JsonResponse
    {

        $this->data['resultAssignedCampaigns'] = $this->campaignAssignRepository->getAssignedCampaignToVendors(Auth::id());
        //dd($this->data['resultAssignedCampaigns']->toArray());

        $filters = array_filter(json_decode($request->get('filters'), true));
        $search_data = $request->get('search');
        $searchValue = $search_data['value'];
        $order = $request->get('order');
        $draw = $request->get('draw');
        $limit = $request->get("length"); // Rows display per page
        $offset = $request->get("start");

        $query = CampaignAssignVendorManager::query();
        $query->whereIn('id', $this->data['resultAssignedCampaigns']->pluck('id')->toArray());
        $query->whereUserId(Auth::id());
        $query->with('campaign');
        $query->with('vendors');

        $totalRecords = $query->count();

        //Search Data
        if(isset($searchValue) && $searchValue != "") {
            $query->where("id", "like", "%$searchValue%");
        }
        //Filters
        if(!empty($filters)) {

        }

        //Order By
        $orderColumn = $order[0]['column'];
        $orderDirection = $order[0]['dir'];
        switch ($orderColumn) {
            case '0': $query->orderBy('id', $orderDirection); break;
            case '1': $query->orderBy('id', $orderDirection); break;
            case '2': $query->orderBy('id', $orderDirection); break;
            case '3': $query->orderBy('id', $orderDirection); break;
            case '4': $query->orderBy('id', $orderDirection); break;
            default: $query->orderBy('id'); break;
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
