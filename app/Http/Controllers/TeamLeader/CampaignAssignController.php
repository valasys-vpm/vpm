<?php

namespace App\Http\Controllers\TeamLeader;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Repository\CampaignAssignRepository\CampaignAssignRepository;
use App\Repository\UserRepository\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CampaignAssignController extends Controller
{
    private $data;
    private $campaignAssignRepository;
    private $userRepository;

    public function __construct(
        CampaignAssignRepository $campaignAssignRepository,
        UserRepository $userRepository
    )
    {
        $this->data = array();
        $this->campaignAssignRepository = $campaignAssignRepository;
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
            'assigned_agents',
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
