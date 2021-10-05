<?php

namespace App\Repository\CampaignRepository;

use App\Models\Campaign;
use App\Models\CampaignCountry;
use App\Models\CampaignSpecification;
use App\Models\PacingDetail;
use App\Repository\CampaignTypeRepository\CampaignTypeRepository;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\DB;

class CampaignRepository implements CampaignInterface
{
    private $campaign;
    private $campaignTypeRepository;

    public function __construct(
        Campaign $campaign,
        CampaignTypeRepository $campaignTypeRepository
    )
    {
        $this->campaign = $campaign;
        $this->campaignTypeRepository = $campaignTypeRepository;
    }

    public function get($filters = array())
    {
        // TODO: Implement get() method.
    }

    public function find($id, $with = array())
    {
        $query = Campaign::query();

        if(!empty($with)) {
            if (in_array('campaignType', $with)) {
                $query->with('campaignType');
            }

            if (in_array('campaignFilter', $with)) {
                $query->with('campaignFilter');
            }

            if (in_array('pacingDetails', $with)) {
                $query->with('pacingDetails');
            }
        }

        return $query->findOrFail($id);
    }

    public function store($attributes)
    {
        $response = array('status' => FALSE, 'message' => 'Something went wrong, please try again.');
        try {
            DB::beginTransaction();

            if(isset($lastRecord)) {
                $lastId = str_pad($lastRecord->id + 1,6,"0",STR_PAD_LEFT);
            } else {
                $lastId = str_pad('1',6,"0",STR_PAD_LEFT);
            }
            $resultCampaignType = $this->campaignTypeRepository->find($attributes['campaign_type_id']);
            $campaignAbbreviation = SiteSetting::where('key', 'Campaign Abbreviation')->first()->value;
            $campaignId = $campaignAbbreviation.$resultCampaignType->name.'-'.$lastId;

            $campaign = new Campaign();
            $campaign->name = $attributes['name'];
            $campaign->campaign_id = $campaignId;
            $campaign->v_mail_campaign_id = $attributes['v_mail_campaign_id'];
            $campaign->campaign_filter_id  = $attributes['campaign_filter_id'];
            $campaign->campaign_type_id  = $attributes['campaign_type_id'];
            $campaign->note = $attributes['note'];

            $campaign->start_date = date('Y-m-d', strtotime($attributes['start_date']));
            $campaign->end_date = date('Y-m-d', strtotime($attributes['end_date']));

            if(isset($attributes['deliver_count']) && $attributes['deliver_count'] > 0) {
                $campaign->deliver_count = $attributes['deliver_count'];
            }
            if(isset($attributes['shortfall_count']) && $attributes['shortfall_count'] > 0) {
                $campaign->shortfall_count = $attributes['shortfall_count'];
            }

            $campaign->allocation = $attributes['allocation'];
            $campaign->campaign_status_id  = $attributes['campaign_status_id'];
            $campaign->pacing  = $attributes['pacing'];

            $campaign->save();
            if($campaign->id) {

                //Save Campaign's Countries
                if(!empty($attributes['country_id']) && count($attributes['country_id'])) {
                    $insertCampaignCountries = array();
                    foreach ($attributes['country_id'] as $country) {
                        array_push($insertCampaignCountries, ['campaign_id' => $campaign->id, 'country_id' => $country]);
                    }
                    CampaignCountry::insert($insertCampaignCountries);
                }

                //Save Campaign's Specifications
                $insertCampaignSpecifications = array();
                if(isset($attributes['specifications']) && !empty($attributes['specifications'])) {
                    foreach ($attributes['specifications'] as $file) {
                        $extension = $file->getClientOriginalExtension();
                        //$filename  = $campaign->campaign_id.'-' . str_shuffle(time()) . '.' . $extension;
                        $filename  = $file->getClientOriginalName();
                        $path = 'public/campaigns/'.$campaign->campaign_id;
                        $result  = $file->storeAs($path, $filename);
                        $insertCampaignSpecifications[] = [
                            'campaign_id' => $campaign->id,
                            'file_name' => $filename,
                            'extension' => $extension
                        ];
                    }
                    CampaignSpecification::insert($insertCampaignSpecifications);
                }

                //Save Pacing Details/Sub-Allocation
                if(isset($attributes['sub-allocation']) && !empty($attributes['sub-allocation'])) {
                    //Pacing Details
                    $insertPacingDetails = array();
                    foreach ($attributes['sub-allocation'] as $date => $sub_allocation) {
                        $insertPacingDetails[] = [
                            'campaign_id' => $campaign->id,
                            'date' => $date,
                            'sub_allocation' => $sub_allocation,
                            'day' => date('w', strtotime($date))
                        ];
                    }
                    PacingDetail::insert($insertPacingDetails);
                    //--Pacing Details
                }
                DB::commit();
                $response = array('status' => TRUE, 'message' => 'Campaign added successfully');
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

    public function update($id, $attributes)
    {
        // TODO: Implement update() method.
    }

    public function destroy($id)
    {
        // TODO: Implement destroy() method.
    }
}
