<?php

namespace App\Http\Controllers\QATeamLeader;

use App\Http\Controllers\Controller;
use App\Models\CampaignAssignQATL;
use App\Models\CampaignAssignQualityAnalyst;
use App\Repository\CampaignAssignRepository\QATLRepository\QATLRepository;
use App\Repository\CampaignAssignRepository\QualityAnalystRepository\QualityAnalystRepository;
use App\Repository\CampaignRepository\CampaignRepository;
use App\Repository\UserRepository\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CampaignAssignController extends Controller
{
    private $data;
    /**
     * @var QATLRepository
     */
    private $QATLRepository;
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var QualityAnalystRepository
     */
    private $qualityAnalystRepository;
    /**
     * @var CampaignRepository
     */
    private $campaignRepository;

    public function __construct(
        QATLRepository $QATLRepository,
        UserRepository $userRepository,
        QualityAnalystRepository $qualityAnalystRepository,
        CampaignRepository $campaignRepository
    )
    {
        $this->data = array();
        $this->QATLRepository = $QATLRepository;
        $this->userRepository = $userRepository;
        $this->qualityAnalystRepository = $qualityAnalystRepository;
        $this->campaignRepository = $campaignRepository;
    }

    public function index()
    {
        $this->data['resultCampaigns'] = $this->QATLRepository->get(array('status' => 1, 'started_at' => NULL));
        $this->data['resultUsers'] = $this->userRepository->get(array(
            'status' => 1,
            'designation_slug' => array('quality_analyst'),
            'reporting_to' => array(Auth::id())
        ));
        //dd($this->data['resultCampaigns'][0]->campaign->toArray());
        return view('qa_team_leader.campaign_assign.list', $this->data);
    }

    public function show($id)
    {
        $this->data['resultCAQATL'] = $this->QATLRepository->find(base64_decode($id));
        $this->data['resultCAQA'] = CampaignAssignQualityAnalyst::where('campaign_assign_qatl_id', $this->data['resultCAQATL']->id)->where('campaign_id', $this->data['resultCAQATL']->campaign_id)->where('status', 1)->first();
        $this->data['resultCampaign'] = $this->campaignRepository->find($this->data['resultCAQATL']->campaign_id);
        //dd($this->data['resultCAQA']->toArray());
        return view('qa_team_leader.campaign_assign.show', $this->data);
    }

    public function store(Request $request)
    {
        try {
            $attributes = $request->all();
            $attributes['started_at'] = date('Y-m-d H:i:s');
            $response = $this->qualityAnalystRepository->store($attributes);
            if($response['status'] == TRUE) {
                $res = $this->QATLRepository->update($attributes['ca_qatl_id'], array('started_at' => date('Y-m-d H:i:s')));
                return redirect()->route('qa_team_leader.campaign_assign.list')->with('success', ['title' => 'Successful', 'message' => $response['message']]);
            } else {
                throw new \Exception('Something went wrong, please try again.', 1);
            }
        } catch (\Exception $exception) {
            return back()->withInput()->with('error', ['title' => 'Error while processing request', 'message' => 'Server error, please try again.']);
        }
    }

    public function submitCampaign($id, Request $request): \Illuminate\Http\JsonResponse
    {
        $finalResponse = array('status' => false, 'message' => 'Something went wrong, please try again.');
        if($request->hasFile('delivery_file')) {
            $attributes = $request->all();
            $attributes['submitted_at'] = date('Y-m-d H:i:s');
            $response = $this->QATLRepository->update(base64_decode($id), $attributes);
            if($response['status']) {
                $finalResponse = array('status' => true, 'message' => 'Campaign submitted successfully');
            } else {
                $finalResponse = array('status' => false, 'message' => $response['message']);
            }
        } else {
            $finalResponse = array('status' => false, 'message' => 'Please upload file');
        }

        return response()->json($finalResponse);
    }

    public function getAssignedCampaigns(Request $request): \Illuminate\Http\JsonResponse
    {

        $filters = array_filter(json_decode($request->get('filters'), true));
        $search_data = $request->get('search');
        $searchValue = $search_data['value'];
        $order = $request->get('order');
        $draw = $request->get('draw');
        $limit = $request->get("length"); // Rows display per page
        $offset = $request->get("start");

        $query = CampaignAssignQATL::query();

        $query->whereUserId(Auth::id());
        $query->with('campaign');
        $query->with('campaign.children');

        $query->with('quality_analyst.user');

        $query->whereNotNull('started_at');

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
