<?php

namespace App\Repository\CampaignAssignRepository\RATLRepository;

use App\Models\CampaignAssignRATL;

class RATLRepository implements RATLInterface
{

    public function get($filters = array())
    {
        $query = CampaignAssignRATL::query();

        if(isset($filters['user_id']) && !empty($filters['user_id'])) {
            $query->whereUserId($filters['user_id']);
        }

        if(isset($filters['user_ids']) && !empty($filters['user_ids'])) {
            $query->whereIn('user_id', $filters['user_ids']);
        }

        return $query->get();
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
