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
        // TODO: Implement get() method.
    }

    public function find($id)
    {
        // TODO: Implement find() method.
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

                foreach ($campaign['users'] as $user) {
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
        // TODO: Implement update() method.
    }

    public function destroy($id)
    {
        // TODO: Implement destroy() method.
    }
}
