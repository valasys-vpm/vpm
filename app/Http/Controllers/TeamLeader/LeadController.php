<?php

namespace App\Http\Controllers\TeamLeader;

use App\Http\Controllers\Controller;
use App\Models\AgentLead;
use App\Models\CampaignAssignAgent;
use App\Models\SuppressionAccountName;
use App\Models\SuppressionDomain;
use App\Models\SuppressionEmail;
use App\Models\TargetDomain;
use App\Repository\AgentLeadRepository\AgentLeadRepository;
use App\Repository\CampaignAssignRepository\AgentRepository\AgentRepository;
use App\Repository\CampaignAssignRepository\RATLRepository\RATLRepository;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    private $data;
    private $agentRepository;
    /**
     * @var RATLRepository
     */
    private $RATLRepository;
    /**
     * @var AgentLeadRepository
     */
    private $agentLeadRepository;

    public function __construct(
        AgentRepository $agentRepository,
        RATLRepository $RATLRepository,
        AgentLeadRepository $agentLeadRepository
    )
    {
        $this->agentRepository = $agentRepository;
        $this->RATLRepository = $RATLRepository;
        $this->agentLeadRepository = $agentLeadRepository;
    }

    public function index($ca_ratl_id)
    {
        $this->data['resultCARATL'] = $this->RATLRepository->find(base64_decode($ca_ratl_id));
        return view('team_leader.lead.list', $this->data);
    }

    public function edit($agent_lead_id)
    {
        $this->data['resultAgentLead'] = $this->agentLeadRepository->find(base64_decode($agent_lead_id));
        $this->data['resultCAAgent'] = $this->agentRepository->find($this->data['resultAgentLead']->ca_agent_id);
        return view('team_leader.lead.edit', $this->data);
    }

    public function update($agent_lead_id, Request $request): \Illuminate\Http\RedirectResponse
    {
        $attributes = $request->all();
        $response = $this->agentLeadRepository->update(base64_decode($agent_lead_id), $attributes);
        if($response['status'] == TRUE) {
            $this->data['resultCAAgent'] = $this->agentRepository->find($response['details']->ca_agent_id);
            return redirect()->route('team_leader.lead.list', base64_encode($this->data['resultCAAgent']->campaign_assign_ratl_id))->with('success', ['title' => 'Successful', 'message' => $response['message']]);
        } else {
            return back()->withInput()->with('error', ['title' => 'Error while processing request', 'message' => $response['message']]);
        }
    }

    public function reject($agent_lead_id, Request $request): \Illuminate\Http\JsonResponse
    {
        $attributes = $request->all();
        $attributes['status'] = 0;
        $response = $this->agentLeadRepository->update(base64_decode($agent_lead_id), $attributes);

        if($response['status'] == TRUE) {
            return response()->json(array('status' => true, 'message' => $response['message']));
        } else {
            return response()->json(array('status' => false, 'message' => $response['message']));
        }
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
        $query->where('status', 1);

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

    public function checkSuppressionEmail($id, Request $request)
    {
        $resultCAAgent = $this->agentRepository->find(base64_decode($id));
        $query = SuppressionEmail::query();
        $query->whereCampaignId($resultCAAgent->campaign_id);
        $query->whereEmail(trim($request->email_address));
        if($query->exists()) {
            return 'false';
        } else {
            return 'true';
        }
    }

    public function checkSuppressionDomain($id, Request $request)
    {
        $resultCAAgent = $this->agentRepository->find(base64_decode($id));
        $query = SuppressionDomain::query();
        $query->whereCampaignId($resultCAAgent->campaign_id);
        if($query->count()) {
            $query->whereDomain(trim($request->company_domain));
            if($query->exists()) {
                return 'false';
            } else {
                return 'true';
            }
        } else {
            return 'true';
        }
    }

    public function checkSuppressionAccountName($id, Request $request)
    {
        $resultCAAgent = $this->agentRepository->find(base64_decode($id));
        $query = SuppressionAccountName::query();
        $query->whereCampaignId($resultCAAgent->campaign_id);
        $query->where('account_name', trim($request->company_name));
        if($query->exists()) {
            return 'false';
        } else {
            return 'true';
        }
    }

    public function checkTargetDomain($id, Request $request)
    {
        $resultCAAgent = $this->agentRepository->find(base64_decode($id));
        $query = TargetDomain::query();
        $query->whereCampaignId($resultCAAgent->campaign_id);
        if($query->count()) {
            $query->whereDomain(trim($request->company_domain));
            if($query->exists()) {
                return 'true';
            } else {
                return 'false';
            }
        } else {
            return 'true';
        }



    }


}
