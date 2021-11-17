<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\CampaignAssignAgent;
use App\Repository\AgentDataRepository\AgentDataRepository;
use App\Repository\AgentLeadRepository\AgentLeadRepository;
use App\Repository\CampaignAssignRepository\AgentRepository\AgentRepository;
use App\Repository\CampaignRepository\CampaignRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CampaignController extends Controller
{
    private $data;
    private $campaignRepository;
    private $agentRepository;
    private $agentLeadRepository;
    /**
     * @var AgentDataRepository
     */
    private $agentDataRepository;

    public function __construct(
        CampaignRepository $campaignRepository,
        AgentRepository $agentRepository,
        AgentLeadRepository $agentLeadRepository,
        AgentDataRepository $agentDataRepository
    )
    {
        $this->data = array();
        $this->campaignRepository = $campaignRepository;
        $this->agentRepository = $agentRepository;
        $this->agentLeadRepository = $agentLeadRepository;
        $this->agentDataRepository = $agentDataRepository;
    }

    public function index()
    {
        return view('agent.campaign.list', $this->data);
    }

    public function show($id)
    {
        try {
            $this->data['countAgentData'] = $this->agentDataRepository->get(array('ca_agent_ids' => [base64_decode($id)]))->count();
            $this->data['resultCAAgent'] = $this->agentRepository->find(base64_decode($id));
            $this->data['resultCampaign'] = $this->campaignRepository->find($this->data['resultCAAgent']->campaign_id);
            if(isset($this->data['resultCampaign']->parent_id) && !empty($this->data['resultCampaign']->parent_id)) {
                $this->data['resultCampaignParent'] = $this->campaignRepository->find($this->data['resultCampaign']->parent_id);
            }
            return view('agent.campaign.show', $this->data);
        } catch (\Exception $exception) {
            return redirect()->route('agent.campaign.list')->with('error', ['title' => 'Error while processing request', 'message' => 'Campaign details not found']);
        }
    }

    public function getCampaigns(Request $request): \Illuminate\Http\JsonResponse
    {
        $filters = array_filter(json_decode($request->get('filters'), true));
        $search_data = $request->get('search');
        $searchValue = $search_data['value'];
        $order = $request->get('order');
        $draw = $request->get('draw');
        $limit = $request->get("length"); // Rows display per page
        $offset = $request->get("start");

        $query = CampaignAssignAgent::query();

        $query->whereUserId(Auth::id());
        $query->with('campaign');
        $query->with('campaign.children');

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

    public function startCampaign($id, Request $request)
    {
        $attributes['started_at'] = date('Y-m-d H:i:s');

        $response = $this->agentRepository->update(base64_decode($id), $attributes);

        if($response['status']) {
            return response()->json(array('status' => true, 'message' => 'Campaign Started'));
        } else {
            return response()->json(array('status' => false, 'message' => 'Data not found'));
        }
    }

    public function submitCampaign($id, Request $request)
    {
        $attributes['submitted_at'] = date('Y-m-d H:i:s');

        $response = $this->agentRepository->update(base64_decode($id), $attributes);

        if($response['status']) {
            return response()->json(array('status' => true, 'message' => 'Campaign submitted successfully'));
        } else {
            return response()->json(array('status' => false, 'message' => 'Data not found'));
        }
    }

    public function restartCampaign($id, Request $request)
    {
        $attributes['submitted_at'] = NULL;

        $response = $this->agentRepository->update(base64_decode($id), $attributes);

        if($response['status']) {
            return response()->json(array('status' => true, 'message' => 'Campaign started successfully'));
        } else {
            return response()->json(array('status' => false, 'message' => 'Data not found'));
        }
    }

}
