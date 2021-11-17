<?php

namespace App\Http\Controllers\TeamLeader;

use App\Http\Controllers\Controller;
use App\Models\AgentLead;
use App\Models\CampaignAssignAgent;
use App\Repository\CampaignAssignRepository\AgentRepository\AgentRepository;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    private $data;
    private $agentRepository;

    public function __construct(
        AgentRepository $agentRepository
    )
    {
        $this->agentRepository = $agentRepository;
    }

    public function index($ca_ratl_id)
    {
        $this->data['resultCARATL'] = $this->agentRepository->find(base64_decode($ca_ratl_id));
        return view('team_leader.lead.list', $this->data);
    }

    public function getAgentLeads(Request $request): \Illuminate\Http\JsonResponse
    {
        $filters = array_filter(json_decode($request->get('filters'), true));
        $search_data = $request->get('search');
        $searchValue = $search_data['value'];
        $order = $request->get('order');
        $draw = $request->get('draw');
        $limit = $request->get("length"); // Rows display per page
        $offset = $request->get("start");

        $query = AgentLead::query();

        if($request->has('ca_ratl_id')) {
            $resultCAAgents = CampaignAssignAgent::where('campaign_assign_ratl_id', base64_decode($request->get('ca_ratl_id')))->get();
            $query->whereIn('ca_agent_id', $resultCAAgents->pluck('id')->toArray());
            //if($resultCAAgents->count())
        }

        $query->with('agent');

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
