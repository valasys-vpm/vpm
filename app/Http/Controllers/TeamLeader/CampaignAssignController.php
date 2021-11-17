<?php

namespace App\Http\Controllers\TeamLeader;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\CampaignAssignAgent;
use App\Models\CampaignAssignRATL;
use App\Models\Data;
use App\Repository\AgentDataRepository\AgentDataRepository;
use App\Repository\CampaignAssignRepository\AgentRepository\AgentRepository;
use App\Repository\CampaignAssignRepository\CampaignAssignRepository;
use App\Repository\CampaignAssignRepository\RATLRepository\RATLRepository;
use App\Repository\CampaignRepository\CampaignRepository;
use App\Repository\DataRepository\DataRepository;
use App\Repository\UserRepository\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Repository\Suppression\Email\EmailRepository as SuppressionEmailRepository;
use App\Repository\Suppression\Domain\DomainRepository as SuppressionDomainRepository;
use App\Repository\Suppression\AccountName\AccountNameRepository as SuppressionAccountNameRepository;
use App\Repository\Target\Domain\DomainRepository as TargetDomainRepository;


class CampaignAssignController extends Controller
{
    private $data;
    private $campaignRepository;
    private $campaignAssignRepository;
    private $RATLRepository;
    private $agentRepository;
    private $userRepository;
    /**
     * @var DataRepository
     */
    private $dataRepository;
    /**
     * @var SuppressionAccountNameRepository
     */
    private $accountNameRepository;
    /**
     * @var SuppressionDomainRepository
     */
    private $suppressionDomainRepository;
    /**
     * @var SuppressionAccountNameRepository
     */
    private $suppressionAccountNameRepository;
    /**
     * @var SuppressionEmailRepository
     */
    private $suppressionEmailRepository;
    /**
     * @var TargetDomainRepository
     */
    private $targetDomainRepository;
    /**
     * @var AgentDataRepository
     */
    private $agentDataRepository;

    public function __construct(
        CampaignRepository $campaignRepository,
        CampaignAssignRepository $campaignAssignRepository,
        RATLRepository $RATLRepository,
        AgentRepository $agentRepository,
        UserRepository $userRepository,
        DataRepository $dataRepository,
        SuppressionEmailRepository $suppressionEmailRepository,
        SuppressionDomainRepository $suppressionDomainRepository,
        SuppressionAccountNameRepository $suppressionAccountNameRepository,
        TargetDomainRepository $targetDomainRepository,
        AgentDataRepository $agentDataRepository
    )
    {
        $this->data = array();
        $this->campaignRepository = $campaignRepository;
        $this->campaignAssignRepository = $campaignAssignRepository;
        $this->RATLRepository = $RATLRepository;
        $this->agentRepository = $agentRepository;
        $this->userRepository = $userRepository;
        $this->dataRepository = $dataRepository;
        $this->suppressionEmailRepository = $suppressionEmailRepository;
        $this->suppressionDomainRepository = $suppressionDomainRepository;
        $this->suppressionAccountNameRepository = $suppressionAccountNameRepository;
        $this->targetDomainRepository = $targetDomainRepository;
        $this->agentDataRepository = $agentDataRepository;
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
            $res = $this->RATLRepository->update($attributes['data'][0]['campaign_assign_ratl_id'], array('started_at' => date('Y-m-d H:i:s')));
            return redirect()->route('team_leader.campaign_assign.list')->with('success', ['title' => 'Successful', 'message' => $response['message']]);
        } else {
            return back()->withInput()->with('error', ['title' => 'Error while processing request', 'message' => $response['message']]);
        }
    }

    public function show($id)
    {
        try {
            //Data for filter
            $this->data['resultFilterJobLevels'] = Data::distinct()->get(['job_level']);
            $this->data['resultFilterJobRoles'] = Data::distinct()->get(['job_role']);
            $this->data['resultFilterEmployeeSizes'] = Data::distinct()->get(['employee_size']);
            $this->data['resultFilterRevenues'] = Data::distinct()->get(['revenue']);
            $this->data['resultFilterCountries'] = Data::distinct()->get(['country']);
            $this->data['resultFilterStates'] = Data::distinct()->get(['state']);

            $this->data['resultCARATL'] = $this->RATLRepository->find(base64_decode($id));
            $this->data['resultCampaign'] = $this->campaignRepository->find($this->data['resultCARATL']->campaign->id);

            //get count if data already assigned
            $this->data['countAgentData'] = $this->agentDataRepository->get(array('ca_ratl_ids' => [$this->data['resultCARATL']->id]))->count();

            return view('team_leader.campaign_assign.show', $this->data);

        } catch (\Exception $exception) {
            return redirect()->route('team_leader.campaign_assign.list')->with('error', ['title' => 'Error while processing request', 'message' => 'Campaign details not found']);
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
        $query->with('campaign.children');
        $query->with('agents');

        $totalRecords = $query->count();

        //Search Data
        if(isset($searchValue) && $searchValue != "") {
            $query->whereHas('campaign', function ($campaign) use($searchValue) {
                $campaign->where("campaign_id", "like", "%$searchValue%");
                $campaign->orWhere("name", "like", "%$searchValue%");
                $campaign->orWhere("deliver_count", "like", "%$searchValue%");
            });
            $query->orWhere("allocation", "like", "%$searchValue%");
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

    public function getData(Request $request): \Illuminate\Http\JsonResponse
    {
        $filters = $request->all();
        //dd($filters);
        $campaign_id = base64_decode($filters['campaign_id']);
        $resultCARATL = $this->RATLRepository->find(base64_decode($filters['ca_ratl_id']));
        $filters['limit'] = $resultCARATL->allocation * 10;
        //get suppression lists
        $suppressionList = array();
        $suppressionList['suppression_email'] = $this->suppressionEmailRepository->get(array('campaign_id' => [$campaign_id]));
        $suppressionList['suppression_domain'] = $this->suppressionDomainRepository->get(array('campaign_id' => [$campaign_id]));
        $suppressionList['suppression_account_name'] = $this->suppressionAccountNameRepository->get(array('campaign_id' => [$campaign_id]));

        //get target lists
        $targetList = array();
        $targetList['target_domain'] = $this->targetDomainRepository->get(array('campaign_id' => [$campaign_id]));

        $result = $this->dataRepository->get($filters, $suppressionList, $targetList);

        if(!empty($result)) {
            return response()->json(array('status' => true, 'message' => $result->count().' records found.', 'data' => $result->pluck('id')->toArray()));
        } else {
            return response()->json(array('status' => false, 'message' => 'Data not found'));
        }
    }

    public function assignData(Request $request): \Illuminate\Http\JsonResponse
    {
        $attributes = $request->all();
        $response = $this->agentDataRepository->assignData($attributes);
        if($response['status'] == TRUE) {
            $countAgentData = $this->agentDataRepository->get(array('ca_ratl_ids' => [base64_decode($attributes['ca_ratl_id'])]))->count();
            return response()->json(array('status' => true, 'message' => $response['message'], 'countAgentData' => $countAgentData));
        } else {
            return response()->json(array('status' => false, 'message' => 'Something went wrong, please try again.'));
        }
    }

}
