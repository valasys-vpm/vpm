<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\AgentLead;
use App\Repository\AgentLeadRepository\AgentLeadRepository;
use App\Repository\CampaignAssignRepository\AgentRepository\AgentRepository;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    private $data;
    private $agentRepository;
    private $agentLeadRepository;

    public function __construct(
        AgentRepository $agentRepository,
        AgentLeadRepository $agentLeadRepository
    )
    {
        $this->data = array();
        $this->agentRepository = $agentRepository;
        $this->agentLeadRepository = $agentLeadRepository;
    }

    public function index($id)
    {
        $this->data['resultCAAgent'] = $this->agentRepository->find(base64_decode($id));
        return view('agent.lead.list', $this->data);
    }

    public function create($id)
    {
        $this->data['resultCAAgent'] = $this->agentRepository->find(base64_decode($id));
        return view('agent.lead.create', $this->data);
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $attributes = $request->all();
        $response = $this->agentLeadRepository->store($attributes);
        if($response['status'] == TRUE) {
            return redirect()->route('agent.lead.list', $attributes['ca_agent_id'])->with('success', ['title' => 'Successful', 'message' => $response['message']]);
        } else {
            return back()->withInput()->with('error', ['title' => 'Error while processing request', 'message' => $response['message']]);
        }
    }

    public function getLeads(Request $request): \Illuminate\Http\JsonResponse
    {
        //dd($request->all());

        $filters = array_filter(json_decode($request->get('filters'), true));
        $search_data = $request->get('search');
        $searchValue = $search_data['value'];
        $order = $request->get('order');
        $draw = $request->get('draw');
        $limit = $request->get("length"); // Rows display per page
        $offset = $request->get("start");

        $query = AgentLead::query();

        if($request->has('ca_agent_id')) {
            $query->where('ca_agent_id', base64_decode($request->get('ca_agent_id')));
        }

        $query->with('campaign');
        $query->with('agent');
        $query->with('ca_agent');

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
