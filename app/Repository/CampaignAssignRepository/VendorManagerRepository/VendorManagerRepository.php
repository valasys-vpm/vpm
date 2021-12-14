<?php

namespace App\Repository\CampaignAssignRepository\VendorManagerRepository;

use App\Models\Campaign;
use App\Models\CampaignAssignVendorManager;
use App\Repository\Notification\VM\VMNotificationRepository;
use Illuminate\Support\Facades\DB;

class VendorManagerRepository implements VendorManagerInterface
{
    /**
     * @var VMNotificationRepository
     */
    private $VMNotificationRepository;

    public function __construct(
        VMNotificationRepository $VMNotificationRepository
    )
    {
        $this->VMNotificationRepository = $VMNotificationRepository;
    }

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
            $resultCampaign = Campaign::findOrFail($attributes['campaign_id']);
            $ca_vm = new CampaignAssignVendorManager();

            $ca_vm->campaign_id = $attributes['campaign_id'];
            $ca_vm->user_id = $attributes['user_id'];
            $ca_vm->display_date = $attributes['display_date'];
            $ca_vm->allocation = $attributes['allocation'];

            if(isset($attributes['started_at']) && !empty($attributes['started_at'])) {
                $ca_vm->started_at = date('Y-m-d H:i:s', strtotime($attributes['started_at']));
            }

            if(isset($attributes['submitted_at']) && !empty($attributes['submitted_at'])) {
                $ca_vm->submitted_at = date('Y-m-d H:i:s', strtotime($attributes['submitted_at']));
            }

            $ca_vm->assigned_by = $attributes['assigned_by'];

            if(array_key_exists('status', $attributes)) {
                $ca_vm->status = $attributes['status'];
            }
            $ca_vm->save();

            if($ca_vm->id) {
                //Send Notification
                $this->VMNotificationRepository->store(array(
                    'sender_id' => $attributes['assigned_by'],
                    'recipient_id' => $attributes['user_id'],
                    'message' => 'New campaign assigned - '.$resultCampaign->name,
                    'url' => route('vendor_manager.campaign.show', base64_encode($ca_vm->id))
                ));
                DB::commit();
                $response = array('status' => TRUE, 'message' => 'Campaign assigned successfully', 'details' => $ca_vm );
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
        // TODO: Implement update() method.
    }

    public function destroy($id)
    {
        // TODO: Implement destroy() method.
    }
}
