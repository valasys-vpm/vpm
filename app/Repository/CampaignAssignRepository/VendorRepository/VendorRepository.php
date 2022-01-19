<?php

namespace App\Repository\CampaignAssignRepository\VendorRepository;

use App\Models\Campaign;
use App\Models\CampaignAssignVendor;
use App\Models\CampaignAssignVendorManager;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VendorRepository implements VendorInterface
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
            DB::beginTransaction();
            $dataVendor = array();
            foreach ($attributes['data'] as $key => $campaign) {

                $resultCampaignAssignVM = CampaignAssignVendorManager::findOrFail($campaign['campaign_assign_vm_id']);

                foreach ($campaign['vendors'] as $vendor) {
                    $dataVendor[] = array(
                        'campaign_id' => $campaign['campaign_id'],
                        'campaign_assign_vm_id' => $campaign['campaign_assign_vm_id'],
                        'vendor_id' => $vendor['vendor_id'],
                        'display_date' => date('Y-m-d', strtotime($resultCampaignAssignVM->display_date)),
                        'allocation' => $vendor['allocation'],
                        'assigned_by' => Auth::id()
                    );
                }
            }

            if(!empty($dataVendor)) {
                if(CampaignAssignVendor::insert($dataVendor)) {
                    DB::commit();
                    $response = array('status' => TRUE, 'message' => 'Campaign assigned successfully');
                } else {
                    throw new \Exception('Something went wrong, please try again.', 1);
                }
            }

        } catch (\Exception $exception) {
            DB::rollBack();
            dd($exception->getMessage());
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
