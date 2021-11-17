<?php

namespace App\Repository\CampaignAssignRepository\EMERepository;

use App\Models\Campaign;
use App\Models\CampaignAssignEME;
use App\Models\CampaignNPFFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EMERepository implements EMEInterface
{
    /**
     * @var CampaignAssignEME
     */
    private $campaignAssignEME;

    public function __construct(
        CampaignAssignEME $campaignAssignEME
    )
    {

        $this->campaignAssignEME = $campaignAssignEME;
    }

    public function get($filters = array())
    {
        // TODO: Implement get() method.
    }

    public function find($id)
    {
        $query = CampaignAssignEME::query();

        return $query->findOrFail($id);
    }

    public function store($attributes)
    {
        $response = array('status' => FALSE, 'message' => 'Something went wrong, please try again.');
        try {
            DB::beginTransaction();
            $resultCAEME = CampaignAssignEME::where('campaign_id', $attributes['campaign_id'])->where('user_id', $attributes['user_id'])->first();
            $campaign_assign_eme = CampaignAssignEME::findOrNew($resultCAEME->id);
            $campaign_assign_eme->campaign_id = $attributes['campaign_id'];
            $campaign_assign_eme->user_id = $attributes['user_id'];
            $campaign_assign_eme->display_date = $attributes['display_date'];
            $campaign_assign_eme->started_at = date('Y-m-d H:i:s');
            $campaign_assign_eme->assigned_by = Auth::id();
            $campaign_assign_eme->save();
            if($campaign_assign_eme->id) {
                //Save NPF Files
                $resultCampaign = Campaign::findOrFail($attributes['campaign_id']);
                $path = 'public/campaigns/'.$resultCampaign->campaign_id.'/quality/npf';
                $dataCampaignNPFFiles = array();
                foreach ($attributes['npf_file'] as $file) {
                    $extension = $file->getClientOriginalExtension();
                    //$filename  = $campaign->campaign_id.'-' . str_shuffle(time()) . '.' . $extension;
                    $filename  = $file->getClientOriginalName();
                    $result  = $file->storeAs($path, $filename);
                    $dataCampaignNPFFiles[] = array(
                        'ca_eme_id' => $campaign_assign_eme->id,
                        'campaign_id' => $attributes['campaign_id'],
                        'asset' => NULL,
                        'file_name' => $filename,
                        'extension' => $extension
                    );
                }
                if(count($dataCampaignNPFFiles) && CampaignNPFFile::insert($dataCampaignNPFFiles)) {
                    DB::commit();
                    $response = array('status' => TRUE, 'message' => 'NPF File(s) uploaded successfully');
                } else {
                    throw new \Exception('Something went wrong, please try again.', 1);
                }
            } else {
                throw new \Exception('Something went wrong, please try again.', 1);
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            $response = array('status' => FALSE, 'message' => 'Something went wrong, please try again.');
        }
        return $response;
    }

    public function update($id, $attributes)
    {
        $response = array('status' => FALSE, 'message' => 'Something went wrong, please try again.');
        try {
            DB::beginTransaction();
            $campaign_assign_eme = CampaignAssignEME::findorFail($id);
            if(isset($attributes['campaign_id']) && !empty($attributes['campaign_id'])) {
                $campaign_assign_eme->campaign_id = $attributes['campaign_id'];
            }

            if(isset($attributes['user_id']) && !empty($attributes['user_id'])) {
                $campaign_assign_eme->user_id = $attributes['user_id'];
            }

            if(isset($attributes['display_date']) && !empty($attributes['display_date'])) {
                $campaign_assign_eme->display_date = $attributes['display_date'];
            }

            if(isset($attributes['started_at']) && !empty($attributes['started_at'])) {
                $campaign_assign_eme->started_at = $attributes['started_at'];
            }

            if(isset($attributes['submitted_at']) && !empty($attributes['submitted_at'])) {
                $campaign_assign_eme->submitted_at = $attributes['submitted_at'];
            }

            if(isset($attributes['assigned_by']) && !empty($attributes['assigned_by'])) {
                $campaign_assign_eme->assigned_by = $attributes['assigned_by'];
            }

            if(array_key_exists('status', $attributes)) {
                $campaign_assign_eme->status = $attributes['status'];
            }

            if($campaign_assign_eme->save()) {
                DB::commit();
                $response = array('status' => TRUE, 'message' => 'EME details updated successfully');
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
