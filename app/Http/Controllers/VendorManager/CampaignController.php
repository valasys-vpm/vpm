<?php

namespace App\Http\Controllers\VendorManager;

use App\Http\Controllers\Controller;
use App\Models\CampaignAssignRATL;
use App\Models\CampaignAssignVendorManager;
use App\Repository\CampaignRepository\CampaignRepository;
use App\Repository\CampaignSpecificationRepository\CampaignSpecificationRepository;
use App\Repository\CampaignStatusRepository\CampaignStatusRepository;
use App\Repository\VendorRepository\VendorRepository;
use App\Repository\CampaignAssignRepository\CampaignAssignRepository;
//use App\Repository\UserRepository\UserRepository;
use Illuminate\Http\Request;
use App\Models\Vendor;
use Auth;


class CampaignController extends Controller
{
    private $data;
    private $vendorRepository;
    private $campaignAssignRepository;
    private $campaignRepository;
    private $campaignStatusRepository;

    public function __construct(
        VendorRepository $vendorRepository,
        CampaignAssignRepository $campaignAssignRepository,
        CampaignRepository $campaignRepository,
        CampaignStatusRepository $campaignStatusRepository
    )
    {
        $this->data = array();
        $this->vendorRepository = $vendorRepository;
        $this->campaignAssignRepository = $campaignAssignRepository;
        $this->campaignRepository = $campaignRepository;
        $this->campaignStatusRepository = $campaignStatusRepository;
    }

    public function index()
    {
        return view('vendor_manager.campaign.list');
    }
    public function show($id)
    {
        try {
            $this->data['resultCampaign'] = $this->campaignRepository->find(base64_decode($id));
            return view('vendor_manager.campaign.show', $this->data);
        } catch (\Exception $exception) {
            return redirect()->route('vendor_manager.campaign.list')->with('error', ['title' => 'Error while processing request', 'message' => 'Campaign details not found']);
        }
    }
    public function getCampaigns(Request $request)
    {
        $filters = array_filter(json_decode($request->get('filters'), true));
        $search_data = $request->get('search');
        $searchValue = $search_data['value'];
        $order = $request->get('order');
        $draw = $request->get('draw');
        $limit = $request->get("length"); // Rows display per page
        $offset = $request->get("start");

        $query = CampaignAssignVendorManager::query();

        //$query->whereIn('id', $campaignIds);
        $query->whereUserId(\Illuminate\Support\Facades\Auth::id())->with('campaign');

        $totalRecords = $query->count();

        //Search Data
        if(isset($searchValue) && $searchValue != "") {
            $query->where("created_at", "like", "%$searchValue%");
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
