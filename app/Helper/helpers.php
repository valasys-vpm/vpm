<?php

use App\Repository\Campaign\History\CampaignHistoryRepository;
use App\Repository\History\HistoryRepository;

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
