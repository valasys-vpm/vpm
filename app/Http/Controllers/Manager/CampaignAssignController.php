<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Repository\CampaignRepository\CampaignRepository;
use App\Repository\UserRepository\UserRepository;
use Illuminate\Http\Request;

class CampaignAssignController extends Controller
{
    private $data;
    private $campaignRepository;
    private $userRepository;

    public function __construct(
        CampaignRepository $campaignRepository,
        UserRepository $userRepository
    )
    {
        $this->data = array();
        $this->campaignRepository = $campaignRepository;
        $this->userRepository = $userRepository;
    }

    public function index()
    {
        $this->data['resultCampaigns'] = $this->campaignRepository->get(array('campaign_to_assign' => 1));
        $this->data['resultUsers'] = $this->userRepository->get(array(
            'status' => 1,
            'designation_slug' => array('ra_team_leader', 'ra_team_leader_business_delivery', 'research_analyst', 'sr_vendor_management_specialist'),
        ));
        //dd($this->data['resultUsers']->toArray());
        return view('manager.campaign_assign.list', $this->data);
    }

}
