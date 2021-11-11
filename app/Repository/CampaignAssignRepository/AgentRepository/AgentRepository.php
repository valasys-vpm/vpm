<?php

namespace App\Repository\CampaignAssignRepository\AgentRepository;

use App\Models\Campaign;
use App\Models\CampaignAssignAgent;
use App\Models\CampaignAssignRATL;
use App\Models\CampaignAssignVendorManager;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AgentRepository implements AgentInterface
{

    public function get($filters = array())
    {
        $query = CampaignAssignAgent::query();

        if(isset($filters['caratl_id']) && !empty($filters['caratl_id'])) {
            $query->where('campaign_assign_ratl_id', $filters['caratl_id']);
        }

        $query->with('user');
        $query->with('userAssignedBy');

        return $query->get();
    }

    public function find($id)
    {
        $query = CampaignAssignAgent::query();
        $query->with('caratl');
        return $query->findOrFail($id);
    }

    public function store($attributes)
    {
        $response = array('status' => FALSE, 'message' => 'Something went wrong, please try again.');
        try {
            //dd($attributes);
            DB::beginTransaction();
            $dataAgent = array();
            foreach ($attributes['data'] as $key => $campaign) {

                $resultCampaign = Campaign::findOrFail($campaign['campaign_id']);
                $resultCampaignAssignRATL = CampaignAssignRATL::findOrFail($campaign['campaign_assign_ratl_id']);

                //Save reporting file to storage
                $filename = null;
                if(!empty($campaign['reporting_file'])) {
                    $path = 'public/campaigns/'.$resultCampaign->campaign_id.'/reporting_file';
                    $file = $campaign['reporting_file'];
                    $extension = $file->getClientOriginalExtension();
                    $filenameOriginal  = $file->getClientOriginalName();
                    $filename  = $campaign['campaign_assign_ratl_id'].'-' . $filenameOriginal . '.' . $extension;
                    $resultFile  = $file->storeAs($path, $filename);
                }

                foreach ($campaign['users'] as $user) {

                    $dataAgent[] = array(
                        'campaign_id' => $campaign['campaign_id'],
                        'campaign_assign_ratl_id' => $campaign['campaign_assign_ratl_id'],
                        'user_id' => $user['user_id'],
                        'display_date' => date('Y-m-d', strtotime($resultCampaignAssignRATL->display_date)),
                        'allocation' => $user['allocation'],
                        'reporting_file' => $filename,
                        'assigned_by' => Auth::id()
                    );

                }
            }

            $flag = 0;

            if(!empty($dataAgent)) {
                if(CampaignAssignAgent::insert($dataAgent)) {
                    DB::commit();
                    $response = array('status' => TRUE, 'message' => 'Campaign assigned successfully');
                } else {
                    throw new \Exception('Something went wrong, please try again.', 1);
                }
            }

        } catch (\Exception $exception) {
            DB::rollBack();
            //dd($exception->getMessage());
            $response = array('status' => FALSE, 'message' => 'Something went wrong, please try again.');
        }
        return $response;
    }

    public function update($id, $attributes)
    {
        $response = array('status' => FALSE, 'message' => 'Something went wrong, please try again.');
        try {
            //dd($attributes);
            DB::beginTransaction();

            $campaign_assign_agent = CampaignAssignAgent::findOrFail($id);
            $resultCampaign = Campaign::findOrFail($campaign_assign_agent->campaign_id);

            if(isset($attributes['campaign_assign_ratl_id']) && $attributes['campaign_assign_ratl_id']) {
                $campaign_assign_agent->campaign_assign_ratl_id = $attributes['campaign_assign_ratl_id'];
            }

            if(isset($attributes['campaign_id']) && $attributes['campaign_id']) {
                $campaign_assign_agent->campaign_id = $attributes['campaign_id'];
            }

            if(isset($attributes['user_id']) && $attributes['user_id']) {
                $campaign_assign_agent->user_id = $attributes['user_id'];
            }

            if(isset($attributes['display_date']) && $attributes['display_date']) {
                $campaign_assign_agent->display_date = $attributes['display_date'];
            }
            if(isset($attributes['allocation']) && $attributes['allocation']) {
                $campaign_assign_agent->allocation = $attributes['allocation'];
            }

            if(isset($attributes['reporting_file']) && $attributes['reporting_file']) {
                //Save reporting file to storage
                $filename = null;
                if(!empty($campaign['reporting_file'])) {
                    $path = 'public/campaigns/'.$resultCampaign->campaign_id.'/reporting_file';
                    $file = $attributes['reporting_file'];
                    $extension = $file->getClientOriginalExtension();
                    $filenameOriginal  = $file->getClientOriginalName();
                    $filename  = $campaign_assign_agent->campaign_assign_ratl_id.'-' . $filenameOriginal . '.' . $extension;
                    $resultFile  = $file->storeAs($path, $filename);
                }
                $campaign_assign_agent->reporting_file = $filename;
            }

            if(isset($attributes['started_at']) && $attributes['started_at']) {
                $campaign_assign_agent->started_at = $attributes['started_at'];
            }
            if(array_key_exists('submitted_at', $attributes)) {
                $campaign_assign_agent->submitted_at = $attributes['submitted_at'];
            }

            if(isset($attributes['assigned_by']) && $attributes['assigned_by']) {
                $campaign_assign_agent->assigned_by = $attributes['assigned_by'];
            }

            if(array_key_exists('status', $attributes)) {
                $campaign_assign_agent->status = $attributes['status'];
            }

            if($campaign_assign_agent->save()) {
                DB::commit();
                $response = array('status' => TRUE, 'message' => 'Details updated successfully');
            } else {
                throw new \Exception('Something went wrong, please try again.', 1);
            }

        } catch (\Exception $exception) {
            DB::rollBack();
            //dd($exception->getMessage());
            $response = array('status' => FALSE, 'message' => 'Something went wrong, please try again.');
        }
        return $response;
    }

    public function destroy($id)
    {
        // TODO: Implement destroy() method.
    }
}
