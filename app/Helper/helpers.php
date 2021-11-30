<?php

use App\Repository\Campaign\History\CampaignHistoryRepository;
use App\Repository\History\HistoryRepository;
use App\Repository\TimeTracker\TimeTrackerRepository;

if(!function_exists('add_campaign_history')) {
    function add_campaign_history($campaign_id, $parent_campaign_id, $message, $data = array())
    {
        if(empty($parent_campaign_id)) {
            $parent_campaign_id = $campaign_id;
        }

        $attributes = array(
            'campaign_id' => $campaign_id,
            'parent_campaign_id' => $parent_campaign_id,
            'message' => $message,
            'data' => $data
        );
        $response = CampaignHistoryRepository::store($attributes);
    }
}

if(!function_exists('add_history')) {
    function add_history($action, $message, $data = array())
    {
        $attributes = array(
            'action' => $action,
            'message' => $message,
            'data' => $data
        );
        $response = HistoryRepository::store($attributes);
    }
}

if(!function_exists('add_time_tracker')) {
    function add_time_tracker($attributes): array
    {
        if(isset($attributes['user_id']) && !empty($attributes['user_id'])) {
            $data['user_id'] = $attributes['user_id'];
        }

        if(isset($attributes['time_in']) && !empty($attributes['time_in'])) {
            $data['time_in'] = $attributes['time_in'];
        }

        if(isset($attributes['time_out']) && !empty($attributes['time_out'])) {
            $data['time_out'] = $attributes['time_out'];
        }

        if(isset($attributes['reason']) && !empty($attributes['reason'])) {
            $data['reason'] = $attributes['reason'];
        }

        if(isset($attributes['type']) && !empty($attributes['type'])) {
            $data['type'] = $attributes['type'];
        }

        return TimeTrackerRepository::store($data);
    }
}

if(!function_exists('update_time_tracker')) {
    function update_time_tracker($id, $attributes): array
    {
        if(isset($attributes['user_id']) && !empty($attributes['user_id'])) {
            $data['user_id'] = $attributes['user_id'];
        }

        if(isset($attributes['time_in']) && !empty($attributes['time_in'])) {
            $data['time_in'] = $attributes['time_in'];
        }

        if(isset($attributes['time_out']) && !empty($attributes['time_out'])) {
            $data['time_out'] = $attributes['time_out'];
        }

        if(isset($attributes['reason']) && !empty($attributes['reason'])) {
            $data['reason'] = $attributes['reason'];
        }

        if(isset($attributes['type']) && !empty($attributes['type'])) {
            $data['type'] = $attributes['type'];
        }

        return TimeTrackerRepository::update($id, $data);
    }
}
