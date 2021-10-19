<?php

namespace App\Http\Controllers\TeamLeader;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\CampaignAssignAgent;
use App\Models\CampaignAssignRATL;
use App\Repository\CampaignAssignRepository\AgentRepository\AgentRepository;
use App\Repository\CampaignAssignRepository\CampaignAssignRepository;
use App\Repository\CampaignAssignRepository\RATLRepository\RATLRepository;
use App\Repository\CampaignRepository\CampaignRepository;
use App\Repository\UserRepository\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CampaignAssignController extends Controller
{
    private $data;
    private $campaignRepository;
    private $campaignAssignRepository;
    private $RATLRepository;
    private $agentRepository;
    private $userRepository;

    public function __construct(
        CampaignRepository $campaignRepository,
        CampaignAssignRepository $campaignAssignRepository,
        RATLRepository $RATLRepository,
        AgentRepository $agentRepository,
        UserRepository $userRepository
    )
    {
        $this->data = array();
        $this->campaignRepository = $campaignRepository;
        $this->campaignAssignRepository = $campaignAssignRepository;
        $this->RATLRepository = $RATLRepository;
        $this->agentRepository = $agentRepository;
        $this->userRepository = $userRepository;
    }

    public function index()
    {
        $this->data['resultCampaigns'] = $this->campaignAssignRepository->getCampaignToAssignForTL(Auth::id());
        $this->data['resultUsers'] = $this->userRepository->get(array(
            'status' => 1,
            'designation_slug' => array('research_analyst'),
            'reporting_to' => array(Auth::id())
        ));
        //dd($this->data['resultCampaigns']->toArray());
        return view('team_leader.campaign_assign.list', $this->data);
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $attributes = $request->all();
        $response = $this->agentRepository->store($attributes);
        if($response['status'] == TRUE) {
            return redirect()->route('team_leader.campaign_assign.list')->with('success', ['title' => 'Successful', 'message' => $response['message']]);
        } else {
            return back()->withInput()->with('error', ['title' => 'Error while processing request', 'message' => $response['message']]);
        }
    }

    public function show($id)
    {
        try {
            $result = $this->RATLRepository->find(base64_decode($id));
            $this->data['resultCampaign'] = $this->campaignRepository->find($result->campaign->id);
            $this->data['resultCampaignAssignedRATL'] = $result;
            //dd($this->data['resultCampaignAssignedRATL']->toArray());
            return view('team_leader.campaign_assign.show', $this->data);
        } catch (\Exception $exception) {
            return redirect()->route('team_leader.campaign.list')->with('error', ['title' => 'Error while processing request', 'message' => 'Campaign details not found']);
        }
    }

    public function getAssignedCampaigns(Request $request): \Illuminate\Http\JsonResponse
    {

        $this->data['resultAssignedCampaigns'] = $this->campaignAssignRepository->getCampaignToAgents(Auth::id());
        //dd($this->data['resultAssignedCampaigns']->toArray());

        $filters = array_filter(json_decode($request->get('filters'), true));
        $search_data = $request->get('search');
        $searchValue = $search_data['value'];
        $order = $request->get('order');
        $draw = $request->get('draw');
        $limit = $request->get("length"); // Rows display per page
        $offset = $request->get("start");

        $query = CampaignAssignRATL::query();
        $query->whereIn('id', $this->data['resultAssignedCampaigns']->pluck('id')->toArray());
        $query->whereUserId(Auth::id());
        $query->with('campaign');
        $query->with('agents');

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

    public function viewAssignmentDetails($id, Request $request): \Illuminate\Http\JsonResponse
    {
        $result = $this->agentRepository->get(array('caratl_id' => base64_decode($id)));
        //dd($result->toArray());
        if(!empty($result)) {
            return response()->json(array('status' => true, 'data' => $result));
        } else {
            return response()->json(array('status' => false, 'message' => 'Data not found'));
        }
    }

}
