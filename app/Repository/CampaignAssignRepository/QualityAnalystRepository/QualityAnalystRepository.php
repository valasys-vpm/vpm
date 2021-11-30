<?php

namespace App\Repository\CampaignAssignRepository\QualityAnalystRepository;

use App\Models\Campaign;
use App\Models\CampaignAssignQualityAnalyst;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QualityAnalystRepository implements QualityAnalystInterface
{

    public function get($filters = array())
    {
        // TODO: Implement get() method.
    }

    public function find($id)
    {
        $query = CampaignAssignQualityAnalyst::query();

        return $query->findOrFail($id);
    }

    public function store($attributes)
    {
        $response = array('status' => FALSE, 'message' => 'Something went wrong, please try again.');
        try {
            //dd($attributes);
            DB::beginTransaction();

            $ca_quality_analyst = new CampaignAssignQualityAnalyst();
            $ca_quality_analyst->campaign_assign_qatl_id = $attributes['ca_qatl_id'];
            $ca_quality_analyst->campaign_id = $attributes['campaign_id'];
            $ca_quality_analyst->user_id = $attributes['user_id'];
            $ca_quality_analyst->display_date = $attributes['display_date'];
            if(isset($attributes['started_at']) && !empty($attributes['started_at'])) {
                $ca_quality_analyst->started_at = date('Y-m-d', strtotime($attributes['started_at']));
            }
            if(isset($attributes['submitted_at']) && !empty($attributes['submitted_at'])) {
                $ca_quality_analyst->submitted_at = date('Y-m-d', strtotime($attributes['submitted_at']));
            }
            $ca_quality_analyst->assigned_by = Auth::id();

            if(isset($attributes['status']) && !empty($attributes['status'])) {
                $ca_quality_analyst->status = $attributes['status'];
            }
            $ca_quality_analyst->save();
            if($ca_quality_analyst->id) {
                DB::commit();
                $response = array('status' => TRUE, 'message' => 'Campaign assigned successfully');
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

    public function update($id, $attributes): array
    {
        $response = array('status' => FALSE, 'message' => 'Something went wrong, please try again.');
        try {
            //dd($attributes);
            DB::beginTransaction();

            $ca_quality_analyst = CampaignAssignQualityAnalyst::findOrFail($id);

            if(isset($attributes['ca_qatl_id']) && !empty($attributes['ca_qatl_id'])) {
                $ca_quality_analyst->campaign_assign_qatl_id = $attributes['ca_qatl_id'];
            }

            if(isset($attributes['campaign_id']) && !empty($attributes['campaign_id'])) {
                $ca_quality_analyst->campaign_id = $attributes['campaign_id'];
            }

            if(isset($attributes['user_id']) && !empty($attributes['user_id'])) {
                $ca_quality_analyst->user_id = $attributes['user_id'];
            }

            if(isset($attributes['display_date']) && !empty($attributes['display_date'])) {
                $ca_quality_analyst->display_date = $attributes['display_date'];
            }

            if(isset($attributes['started_at']) && !empty($attributes['started_at'])) {
                $ca_quality_analyst->started_at = $attributes['started_at'];
            }

            if(isset($attributes['submitted_at']) && !empty($attributes['submitted_at'])) {
                $ca_quality_analyst->submitted_at = $attributes['submitted_at'];
            }

            if(isset($attributes['final_file']) && !empty($attributes['final_file'])) {
                $file = $attributes['final_file'];
                $resultCampaign = Campaign::findOrFail($ca_quality_analyst->campaign_id);
                $path = 'public/campaigns/'.$resultCampaign->campaign_id.'/quality/qa_final';
                $extension = $file->getClientOriginalExtension();
                $filename  = $file->getClientOriginalName();
                if($file->storeAs($path, $filename)) {
                    $ca_quality_analyst->file_name = $filename;
                } else {
                    throw new \Exception('Please check file and try again.', 1);
                }
            }

            if(isset($attributes['assigned_by']) && !empty($attributes['assigned_by'])) {
                $ca_quality_analyst->assigned_by = $attributes['assigned_by'];
            }

            if(array_key_exists('status', $attributes)) {
                $ca_quality_analyst->status = $attributes['status'];
            }

            if($ca_quality_analyst->save()) {
                DB::commit();
                $response = array('status' => TRUE, 'message' => 'Details updated successfully');
            } else {
                throw new \Exception('Something went wrong, please try again.', 1);
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            $response = array('status' => FALSE, 'message' => 'Something went wrong, please try again.');
        }
        return $response;
    }

    public function destroy($id)
    {
        // TODO: Implement destroy() method.
    }
}
