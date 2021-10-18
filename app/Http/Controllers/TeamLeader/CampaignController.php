<?php

namespace App\Http\Controllers\TeamLeader;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\CampaignAssignRATL;
use App\Repository\CampaignAssignRepository\RATLRepository\RATLRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CampaignController extends Controller
{
    private $data;
    private $RATLRepository;

    public function __construct(
        RATLRepository $RATLRepository
    )
    {
        $this->data = array();
        $this->RATLRepository = $RATLRepository;
    }

    public function index()
    {
        return view('team_leader.campaign.list');
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

        $query = CampaignAssignRATL::query();

        //$query->whereIn('id', $campaignIds);
        $query->whereUserId(Auth::id())->with('campaign');

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
