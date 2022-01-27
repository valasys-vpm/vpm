<?php

namespace App\Repository\CampaignSpecificationRepository;

use App\Models\CampaignSpecification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CampaignSpecificationRepository implements CampaignSpecificationInterface
{
    private $campaignSpecification;

    public function __construct(CampaignSpecification $campaignSpecification)
    {
        $this->campaignSpecification = $campaignSpecification;
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
        $response = array('status' => FALSE, 'message' => 'Something went wrong, please try again.');
        try {
            DB::beginTransaction();

            $campaignSpecification = CampaignSpecification::findOrFail($id);
            $file_path = 'public/campaigns/'.$campaignSpecification->campaign->campaign_id.'/'.$campaignSpecification->file_name;
            //--Campaign Specifications
            if(Storage::exists($file_path)) {
                Storage::delete($file_path);
                $campaignSpecification->delete();
                $response = array('status' => TRUE, 'message' => 'Campaign specification removed successfully');
                DB::commit();
            } else {
                $campaignSpecification->delete();
                $response = array('status' => TRUE, 'message' => 'Campaign specification removed successfully');
                DB::commit();
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            $response = array('status' => FALSE, 'message' => 'Something went wrong, please try again.');
        }
        return $response;
    }


}
