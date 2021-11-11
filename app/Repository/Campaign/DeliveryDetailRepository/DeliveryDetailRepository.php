<?php

namespace App\Repository\Campaign\DeliveryDetailRepository;

use App\Models\CampaignDeliveryDetail;

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

    public function update($id, $attributes)
    {
        // TODO: Implement update() method.
    }

    public function destroy($id)
    {
        // TODO: Implement destroy() method.
    }
}
