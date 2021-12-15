<?php

use App\Repository\Campaign\History\CampaignHistoryRepository;
use App\Repository\History\HistoryRepository;
use App\Repository\TimeTracker\TimeTrackerRepository;
use Illuminate\Support\Facades\Http;

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

if(!function_exists('get_history_message')) {
    function get_history_message($oldData, $newData)
    {
        $historyMessage = '';

        if(!empty($newData)) {
            foreach ($newData as $key => $value) {
                $keyName = $key;
                if(strpos('_id', $key)) {
                    $keyName = str_replace('_id', '', $key);
                }

                if(!empty($oldData[$key])) {
                    $old = $oldData[$key];
                } else {
                    $old = 'Not Updated';
                }
                $historyMessage .= '<br>- '.ucwords(str_replace('_',' ', $keyName)).': from <b>'.$old.'</b> to <b>'.$newData[$key].'</b>';
            }
        }
        return $historyMessage;
    }
}

if(!function_exists('send_mail')) {
    function send_mail($details)
    {
        $email_data = array();

        if(array_key_exists('to', $details) && !empty($details['to'])) {
            $email_data['to'] = $details['to'];
        }

        if(array_key_exists('cc', $details) && !empty($details['cc'])) {
            $email_data['cc'] = $details['cc'];
        }

        if(array_key_exists('bcc', $details) && !empty($details['bcc'])) {
            $email_data['bcc'] = $details['bcc'];
        }

        if(array_key_exists('subject', $details) && !empty($details['subject'])) {
            $email_data['subject'] = $details['subject'];
        }

        if(array_key_exists('body', $details) && !empty($details['body'])) {
            $email_data['body'] = $details['body'];
        }

        return Http::post('https://api.valasysb2bmarketing.com/api/email/send', $email_data);
    }
}

if(!function_exists('secured_url')) {
    function secured_url($url)
    {
        $url_details = parse_url($url);

        if($url_details['scheme'] == 'http') {
            return str_replace("http://","https://", $url);
        } else {
            return $url;
        }

    }
}

if(!function_exists('is_live_server')) {
    function is_live_server()
    {
        if(env('APP_URL') == 'http://localhost/vpm' || env('APP_URL') == 'https://testing.valasysmedia.com') {
            return false;
        } else {
            return true;
        }

    }
}
