<?php

namespace App\Http\Controllers\VendorManager;

use App\Http\Controllers\Controller;
use App\Repository\VendorRepository\VendorRepository;
use App\Repository\CampaignAssignRepository\CampaignAssignRepository;
//use App\Repository\UserRepository\UserRepository;
use Illuminate\Http\Request;
use App\Models\Vendor;
use Auth;


class CampaignController extends Controller
{
    private $data;
    private $vendorRepository;
    private $campaignAssignRepository;

    public function __construct(
        VendorRepository $vendorRepository,
        CampaignAssignRepository $campaignAssignRepository
    )
    {
        $this->data = array();
        $this->vendorRepository = $vendorRepository;
        $this->campaignAssignRepository = $campaignAssignRepository;
    }

    public function index()
    {
        $this->data['resultCampaigns'] = $this->campaignAssignRepository->getAssignedCampaigns();
//        $this->data['resultUsers'] = $this->userRepository->get(array(
//            'status' => 1,
//            'designation_slug' => array('ra_team_leader', 'ra_team_leader_business_delivery', 'research_analyst', 'sr_vendor_management_specialist'),
//        ));
//        dd($this->data->toArray());
        $this->data['resultVendors'] = $this->vendorRepository->get(array('status' => 1));
        dd($this->data);

        return view('vendor_management.campaign.list',$this->data);
    }
}
