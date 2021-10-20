<?php

namespace App\Repository\CampaignAssignRepository;

use App\Models\Campaign;
use App\Models\CampaignAssignAgent;
use App\Models\CampaignAssignRATL;
use App\Models\CampaignAssignVendor;
use App\Models\CampaignAssignVendorManager;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CampaignAssignRepository implements CampaignAssignInterface
{

    public function __construct()
    {

    }

    public function getAssignedCampaigns($filters = array())
    {
        $resultAssignedCampaigns = array();
        //get campaigns already assigned
        $result['RATL'] = CampaignAssignRATL::select('campaign_id')->whereStatus(1)->get();
        $result['AGENT'] = CampaignAssignAgent::select('campaign_id')->whereStatus(1)->get();
        $result['VM'] = CampaignAssignVendorManager::select('campaign_id')->whereStatus(1)->get();

        if(!empty($result['RATL'])) {
            $resultAssignedCampaigns = array_unique (array_merge ($resultAssignedCampaigns, $result['RATL']->pluck('campaign_id')->toArray()));
        }

        if(!empty($result['AGENT'])) {
            $resultAssignedCampaigns = array_unique (array_merge ($resultAssignedCampaigns, $result['AGENT']->pluck('campaign_id')->toArray()));
        }

        if(!empty($result['VM'])) {
            $resultAssignedCampaigns = array_unique (array_merge ($resultAssignedCampaigns, $result['VM']->pluck('campaign_id')->toArray()));
        }


        $resultAssignedCampaigns = Campaign::whereIn('id', $resultAssignedCampaigns)->get();

        $resultAssignedCampaignIds = array();
        if($resultAssignedCampaigns->count()) {
            foreach ($resultAssignedCampaigns as $campaign) {
                if($campaign->parent_id) {
                    $resultAssignedCampaignIds[] = $campaign->parent_id;
                } else {
                    $resultAssignedCampaignIds[] = $campaign->id;
                }
            }
        }
        $query = Campaign::query();
        $query->whereIn('id', $resultAssignedCampaignIds);
        return $query->get();
    }

    public function getCampaignToAgents($id, $filters = array())
    {
        //get TL's member list
        $resultUsers = User::whereReportingUserId($id)->whereStatus(1)->get();

        $resultAssignedCampaigns = array();
        //get campaigns already assigned to agents
        $result['AGENT'] = CampaignAssignAgent::select('campaign_id')
            ->whereIn('user_id', $resultUsers->pluck('id')->toArray())
            ->whereStatus(1)
            ->get();

        if(!empty($result['AGENT'])) {
            $resultAssignedCampaigns = array_unique (array_merge ($resultAssignedCampaigns, $result['AGENT']->pluck('campaign_id')->toArray()));
        }

        $query = CampaignAssignRATL::query();
        $query->with('campaign');
        $query->whereUserId($id);
        $query->whereIn('campaign_id', $resultAssignedCampaigns);
        return $query->get();
    }

    public function getAssignedCampaignToVendors($id, $filters = array())
    {
        //get Vendor's list
        $resultVendors = Vendor::whereStatus(1)->get();
        $resultAssignedCampaigns = array();
        //get campaigns already assigned to agents
        $result['VENDOR'] = CampaignAssignVendor::select('campaign_id')
            ->whereIn('vendor_id', $resultVendors->pluck('id')->toArray())
            ->whereStatus(1)
            ->get();

        if(!empty($result['VENDOR'])) {
            $resultAssignedCampaigns = array_unique (array_merge ($resultAssignedCampaigns, $result['VENDOR']->pluck('campaign_id')->toArray()));
        }

        $query = CampaignAssignVendorManager::query();
        $query->with('campaign');
        $query->whereUserId($id);
        $query->whereIn('campaign_id', $resultAssignedCampaigns);
        return $query->get();
    }

    public function getCampaignToAssignForTL($id, $filters = array())
    {
        //get TL's member list
        $resultUsers = User::whereReportingUserId($id)->whereStatus(1)->get();

        $resultAssignedCampaigns = array();
        //get campaigns already assigned to agents
        $result['AGENT'] = CampaignAssignAgent::select('campaign_id')
            ->whereIn('user_id', $resultUsers->pluck('id')->toArray())
            ->whereStatus(1)
            ->get();

        if(!empty($result['AGENT'])) {
            $resultAssignedCampaigns = array_unique (array_merge ($resultAssignedCampaigns, $result['AGENT']->pluck('campaign_id')->toArray()));
        }

        $query = CampaignAssignRATL::query();
        $query->with('campaign');
        $query->whereUserId($id);
        $query->whereNotIn('campaign_id', $resultAssignedCampaigns);
        return $query->get();
    }

    public function getCampaignToAssignForVM($id, $filters = array())
    {
        //get Vendor's member list
        $resultVendors = Vendor::whereStatus(1)->get();

        $resultAssignedCampaigns = array();
        //get campaigns already assigned to agents
        $result['VENDOR'] = CampaignAssignVendor::select('campaign_id')
            ->whereIn('vendor_id', $resultVendors->pluck('id')->toArray())
            ->whereStatus(1)
            ->get();

        if(!empty($result['VENDOR'])) {
            $resultAssignedCampaigns = array_unique (array_merge ($resultAssignedCampaigns, $result['VENDOR']->pluck('campaign_id')->toArray()));
        }

        $query = CampaignAssignVendorManager::query();
        $query->with('campaign');
        $query->whereUserId($id);
        $query->whereNotIn('campaign_id', $resultAssignedCampaigns);
        return $query->get();
    }

    public function getCampaignToAssign($filters = array())
    {
        $resultAssignedCampaigns = array();
        //get campaigns already assigned
        $result['RATL'] = CampaignAssignRATL::select('campaign_id')->whereStatus(1)->get();
        $result['AGENT'] = CampaignAssignAgent::select('campaign_id')->whereStatus(1)->get();
        $result['VM'] = CampaignAssignVendorManager::select('campaign_id')->whereStatus(1)->get();

        if(!empty($result['RATL'])) {
            $resultAssignedCampaigns = array_unique (array_merge ($resultAssignedCampaigns, $result['RATL']->pluck('campaign_id')->toArray()));
        }

        if(!empty($result['AGENT'])) {
            $resultAssignedCampaigns = array_unique (array_merge ($resultAssignedCampaigns, $result['AGENT']->pluck('campaign_id')->toArray()));
        }

        if(!empty($result['VM'])) {
            $resultAssignedCampaigns = array_unique (array_merge ($resultAssignedCampaigns, $result['VM']->pluck('campaign_id')->toArray()));
        }

        $resultCampaignIds = array();
        //get campaign with live incremental
        $resultCampaigns = Campaign::whereNull('parent_id')->whereNotIn('id', $resultAssignedCampaigns)->get();

        if(!empty($resultCampaigns)) {
            foreach ($resultCampaigns as $campaign) {
                if($campaign->children->count()) {
                    foreach ($campaign->children as $incremental) {
                        if($incremental->campaign_status_id == 1) {
                            $resultCampaignIds[] = $incremental->id;
                        }
                    }
                } else {
                    $resultCampaignIds[] = $campaign->id;
                }
            }
        }
        $query = Campaign::query();
        $query->whereIn('id', $resultCampaignIds);
        return $query->get();
    }

    public function getAssignedRATL($id)
    {
        $query = CampaignAssignRATL::query();
        $query->whereCampaignId($id);

        $query->with('agents');
        $query->with('user');
        $query->with('userAssignedBy');

        return $query->get();
    }

    public function store($attributes): array
    {
        $response = array('status' => FALSE, 'message' => 'Something went wrong, please try again.');
        try {
            DB::beginTransaction();
            $dataRATL = $dataAgent = $dataVM = array();
            foreach ($attributes['data'] as $key => $campaign) {
                foreach ($campaign['users'] as $user) {
                    //Check user type
                    $resultUser = User::findOrFail($user['user_id']);
                    switch ($resultUser->designation->slug) {
                        case 'ra_team_leader' :
                            $dataRATL[] = array(
                                'campaign_id' => $campaign['campaign_id'],
                                'user_id' => $user['user_id'],
                                'display_date' => date('Y-m-d', strtotime($campaign['display_date'])),
                                'allocation' => $user['allocation'],
                                'assigned_by' => Auth::id()
                            );
                            break;
                        case 'research_analyst' :
                            $dataAgent[] = array(
                                'campaign_id' => $campaign['campaign_id'],
                                'campaign_assign_ratl_id ' => $campaign['campaign_assign_ratl_id'],
                                'user_id' => $user['user_id'],
                                'display_date' => date('Y-m-d', strtotime($campaign['display_date'])),
                                'allocation' => $user['allocation'],
                                'reporting_file' => '',
                                'assigned_by' => Auth::id()
                            );
                            break;
                        case 'sr_vendor_management_specialist' :
                            $dataVM[] = array(
                                'campaign_id' => $campaign['campaign_id'],
                                'user_id' => $user['user_id'],
                                'display_date' => date('Y-m-d', strtotime($campaign['display_date'])),
                                'allocation' => $user['allocation'],
                                'assigned_by' => Auth::id()
                            );
                            break;
                    }
                }
            }

            $flag = 0;

            if(!empty($dataRATL)) {
                if(CampaignAssignRATL::insert($dataRATL)) {
                    $flag = 1;
                } else {
                    throw new \Exception('Something went wrong, please try again.', 1);
                }
            }

            if(!empty($dataAgent)) {
                if(CampaignAssignAgent::insert($dataAgent)) {
                    $flag = 1;
                } else {
                    throw new \Exception('Something went wrong, please try again.', 1);
                }
            }

            if(!empty($dataVM)) {
                if(CampaignAssignVendorManager::insert($dataVM)) {
                    $flag = 1;
                } else {
                    throw new \Exception('Something went wrong, please try again.', 1);
                }
            }

            if($flag) {
                DB::commit();
                $response = array('status' => TRUE, 'message' => 'Campaign assigned successfully');
            } else {
                throw new \Exception('Something went wrong, please try again.', 1);
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            dd($exception->getMessage());
            $response = array('status' => FALSE, 'message' => 'Something went wrong, please try again.');
        }
        return $response;
    }
}