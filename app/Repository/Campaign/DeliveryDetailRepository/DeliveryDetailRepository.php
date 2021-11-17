<?php

namespace App\Repository\Campaign\DeliveryDetailRepository;

use App\Models\Campaign;
use App\Models\CampaignDeliveryDetail;
use Illuminate\Support\Facades\DB;

class DeliveryDetailRepository implements DeliveryDetailInterface
{
    /**
     * @var CampaignDeliveryDetail
     */
    private $campaignDeliveryDetail;

    public function __construct(
        CampaignDeliveryDetail $campaignDeliveryDetail
    )
    {
        $this->campaignDeliveryDetail = $campaignDeliveryDetail;
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
        // TODO: Implement store() method.
    }

    public function update($id = 0, $attributes)
    {
        $response = array('status' => FALSE, 'message' => 'Something went wrong, please try again.');
        try {
            DB::beginTransaction();


            $delivery_detail = CampaignDeliveryDetail::findOrNew($id);

            if(!empty($delivery_detail->campaign_id)) {
                $resultCampaign = Campaign::findOrFail($delivery_detail->campaign_id);
            } else {
                $resultCampaign = Campaign::findOrFail($attributes['campaign_id']);
            }

            if(isset($attributes['campaign_id']) && !empty($attributes['campaign_id'])) {
                $delivery_detail->campaign_id = $attributes['campaign_id'];
            }

            if(isset($attributes['lead_sent']) && !empty($attributes['lead_sent'])) {
                $delivery_detail->lead_sent = $attributes['lead_sent'];
            }

            if(isset($attributes['lead_approved']) && !empty($attributes['lead_approved'])) {
                $delivery_detail->lead_approved = $attributes['lead_approved'];
            }

            if(isset($attributes['lead_rejected']) && !empty($attributes['lead_rejected'])) {
                $delivery_detail->lead_rejected = $delivery_detail->lead_sent - $delivery_detail->lead_approved;
            }

            if(isset($attributes['lead_available']) && !empty($attributes['lead_available'])) {
                $delivery_detail->lead_available = $attributes['lead_available'];
            }

            if(isset($attributes['lead_pending']) && !empty($attributes['lead_pending'])) {
                $delivery_detail->lead_pending = $resultCampaign->allocation - $delivery_detail->lead_approved;
            }

            if(isset($attributes['campaign_progress']) && !empty($attributes['campaign_progress'])) {
                $delivery_detail->campaign_progress = $attributes['campaign_progress'];
            }

            if(isset($attributes['updated_by']) && !empty($attributes['updated_by'])) {
                $delivery_detail->updated_by = $attributes['updated_by'];
            }

            $delivery_detail->save();
            if($delivery_detail->id) {
                DB::commit();
                $response = array('status' => TRUE, 'message' => 'Campaign delivery details updated successfully');
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

    public function destroy($id)
    {
        // TODO: Implement destroy() method.
    }
}
