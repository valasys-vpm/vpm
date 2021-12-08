<?php

namespace App\Repository\Notification\RA;

use App\Models\RANotification;
use Illuminate\Support\Facades\DB;

class RANotificationRepository implements RANotificationInterface
{
    /**
     * @var RANotification
     */
    private $RANotification;

    public function __construct(RANotification $RANotification)
    {
        $this->RANotification = $RANotification;
    }

    public function get($filters = array())
    {
        $query = RANotification::query();

        if(array_key_exists('read_status', $filters)) {
            $query->whereReadStatus($filters['read_status']);
        }

        return $query->get();
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
            $notification = new RANotification();

            $notification->sender_id = $attributes['sender_id'];
            $notification->recipient_id = $attributes['recipient_id'];
            if(array_key_exists('read_status', $attributes)) {
                $notification->read_status = $attributes['read_status'];
            }
            $notification->message = $attributes['message'];
            if(isset($attributes['url']) && !empty($attributes['url'])) {
                $notification->url = $attributes['url'];
            }
            $notification->save();

            if($notification->id) {
                DB::commit();
                $response = array('status' => TRUE, 'message' => 'Notification updated successfully', 'details' => $notification);
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
            $notification = RANotification::findOrFail($id);
            if(isset($attributes['sender_id']) && !empty($attributes['sender_id'])) {
                $notification->sender_id = $attributes['sender_id'];
            }
            if(isset($attributes['recipient_id']) && !empty($attributes['recipient_id'])) {
                $notification->recipient_id = $attributes['recipient_id'];
            }
            if(isset($attributes['message']) && !empty($attributes['message'])) {
                $notification->message = $attributes['message'];
            }
            if(isset($attributes['url']) && !empty($attributes['url'])) {
                $notification->url = $attributes['url'];
            }
            if(array_key_exists('read_status', $attributes)) {
                $notification->read_status = $attributes['read_status'];
            }
            $notification->save();
            if($notification->id) {
                DB::commit();
                $response = array('status' => TRUE, 'message' => 'Notification updated successfully', 'data' => $notification);
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
