<?php

namespace App\Http\Controllers\TeamLeader;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Models\CampaignAssignRATL;
use App\Repository\CampaignStatusRepository\CampaignStatusRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    private $data;
    /**
     * @var CampaignStatusRepository
     */
    private $campaignStatusRepository;

    public function __construct(
        CampaignStatusRepository $campaignStatusRepository
    )
    {
        $this->data = array();
        $this->campaignStatusRepository = $campaignStatusRepository;

        $this->data['resultCampaignStatuses'] = $this->campaignStatusRepository->get(array('status' => 1));
    }

    public function index()
    {
        return view('team_leader.dashboard', $this->data);
    }

    public function getCounts(Request $request)
    {
        //get RATL's Campaigns
        $resultMyCampaigns = CampaignAssignRATL::where('user_id', Auth::id())->whereIn('status', [0,1])->get();
        //dd($resultMyCampaigns->pluck('campaign_id')->toArray());
        $response = array();

        foreach($this->data['resultCampaignStatuses'] as $campaignStatus) {
            $response[$campaignStatus->slug] = 0;
        }

        $query = Campaign::query();
        $query->whereIn('id', $resultMyCampaigns->pluck('campaign_id')->toArray());
        $resultCampaigns = $query->get();

        //dd($resultCampaigns->toArray());

        foreach($resultCampaigns as $campaign) {
            if($campaign->children->count()) {
                $campaign_status_id = $campaign->children[0]->campaign_status_id;
            } else {
                $campaign_status_id = $campaign->campaign_status_id;
            }

            switch ($campaign_status_id) {
                case 1:  $response['live']++;break;
                case 2:  $response['paused']++;break;
                case 3:  $response['cancelled']++;break;
                case 4:  $response['delivered']++;break;
                case 5:  $response['reactivated']++;break;
                case 6:  $response['shortfall']++;break;
            }
        }
        //dd($response);
        return response()->json($response);
    }
}
