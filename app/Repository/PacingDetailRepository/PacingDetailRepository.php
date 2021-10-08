<?php

namespace App\Repository\PacingDetailRepository;

use App\Models\PacingDetail;

class PacingDetailRepository implements PacingDetailInterface
{
    private $pacingDetail;

    public function __construct(PacingDetail $pacingDetail)
    {
        $this->pacingDetail = $pacingDetail;
    }

    public function get($campaign_id = null, $filters = array())
    {
        $query = PacingDetail::query();

        if(isset($campaign_id) && !empty($campaign_id)) {
            $query->where('campaign_id', $campaign_id);
        }

        if(isset($filters['month']) && !empty($filters['month'])) {
            $query->whereMonth('date', $filters['month']);
        }

        if(isset($filters['year']) && !empty($filters['year'])) {
            $query->whereYear('date', $filters['year']);
        }

        return $query->get();
    }

    public function find($id)
    {
        // TODO: Implement find() method.
    }

    public function store($campaign_id, $attributes)
    {
        // TODO: Implement store() method.
    }

    public function update($campaign_id, $attributes)
    {
        // TODO: Implement update() method.
    }

    public function destroy($id)
    {
        // TODO: Implement destroy() method.
    }
}
