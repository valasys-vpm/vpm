<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Repository\CampaignAssignRepository\CampaignAssignRepository;
use App\Repository\CampaignRepository\CampaignRepository;
use App\Repository\UserRepository\UserRepository;
use Illuminate\Http\Request;

class CampaignAssignController extends Controller
{
    private $data;
    private $campaignRepository;
    private $userRepository;
    private $campaignAssignRepository;

    public function __construct(
        CampaignRepository $campaignRepository,
        UserRepository $userRepository,
        CampaignAssignRepository $campaignAssignRepository
    )
    {
        $this->data = array();
        $this->campaignRepository = $campaignRepository;
        $this->userRepository = $userRepository;
        $this->campaignAssignRepository = $campaignAssignRepository;
    }

    public function index()
    {
        $this->data['resultCampaigns'] = $this->campaignAssignRepository->getCampaignToAssign();
        $this->data['resultUsers'] = $this->userRepository->get(array(
            'status' => 1,
            'designation_slug' => array('ra_team_leader', 'ra_team_leader_business_delivery', 'research_analyst', 'sr_vendor_management_specialist'),
        ));
        //dd($this->data['resultCampaigns']->toArray());
        return view('manager.campaign_assign.list', $this->data);
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $attributes = $request->all();
        $response = $this->campaignAssignRepository->store($attributes);
        if($response['status'] == TRUE) {
            return redirect()->route('manager.campaign_assign.list')->with('success', ['title' => 'Successful', 'message' => $response['message']]);
        } else {
            return back()->withInput()->with('error', ['title' => 'Error while processing request', 'message' => $response['message']]);
        }
    }

    public function show($id)
    {
        try {
            $this->data['resultCampaign'] = $this->campaignRepository->find(base64_decode($id));
            //dd($this->data['resultCampaign']->children->toArray());
            return view('manager.campaign_assign.show', $this->data);
        } catch (\Exception $exception) {
            return redirect()->route('manager.campaign.list')->with('error', ['title' => 'Error while processing request', 'message' => 'Campaign details not found']);
        }
    }

    public function getAssignedCampaigns(Request $request): \Illuminate\Http\JsonResponse
    {

        $this->data['resultAssignedCampaigns'] = $this->campaignAssignRepository->getAssignedCampaigns();
        //dd($this->data['resultAssignedCampaigns']->pluck('id')->toArray());

        $filters = array_filter(json_decode($request->get('filters'), true));
        $search_data = $request->get('search');
        $searchValue = $search_data['value'];
        $order = $request->get('order');
        $draw = $request->get('draw');
        $limit = $request->get("length"); // Rows display per page
        $offset = $request->get("start");

        $query = Campaign::query();
        $query->whereIn('id', $this->data['resultAssignedCampaigns']->pluck('id')->toArray());
        $query->with([
            'assigned_ratls',
            'assigned_agents',
            'assigned_vendor_managers',
            'children.assigned_ratls',
            'children.assigned_agents',
            'children.assigned_vendor_managers',
            ]);
        $totalRecords = $query->count();

        //Search Data
        if(isset($searchValue) && $searchValue != "") {
            $query->where("name", "like", "%$searchValue%");
        }
        //Filters
        if(!empty($filters)) {

        }


        //Order By
        $orderColumn = $order[0]['column'];
        $orderDirection = $order[0]['dir'];
        switch ($orderColumn) {
            case '0': $query->orderBy('name', $orderDirection); break;
            case '1': $query->orderBy('name', $orderDirection); break;
            case '2': $query->orderBy('name', $orderDirection); break;
            case '3': $query->orderBy('name', $orderDirection); break;
            case '4': $query->orderBy('name', $orderDirection); break;
            default: $query->orderBy('name'); break;
        }

        $totalFilterRecords = $query->count();
        if($limit > 0) {
            $query->offset($offset);
            $query->limit($limit);
        }
        //Do not take incremental and reactivated
        $query->whereNull('parent_id');
        $query->with('children', function($children) {
            $children->orderBy('created_at', 'DESC');
        });


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
