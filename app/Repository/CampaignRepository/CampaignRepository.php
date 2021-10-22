<?php

namespace App\Repository\CampaignRepository;

use App\Models\Campaign;
use App\Models\CampaignCountry;
use App\Models\CampaignFile;
use App\Models\CampaignSpecification;
use App\Models\PacingDetail;
use App\Repository\CampaignFile\CampaignFileRepository;
use App\Repository\CampaignTypeRepository\CampaignTypeRepository;
use App\Models\SiteSetting;

use App\Repository\Suppression\AccountName\AccountNameRepository as SuppressionAccountNameRepository;
use App\Repository\Suppression\Domain\DomainRepository as SuppressionDomainRepository;
use App\Repository\Suppression\Email\EmailRepository as SuppressionEmailRepository;

use App\Repository\Target\AccountName\AccountNameRepository as TargetAccountNameRepository;
use App\Repository\Target\Domain\DomainRepository as TargetDomainRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class CampaignRepository implements CampaignInterface
{
    private $campaign;
    private $campaignTypeRepository;
    private $campaignFileRepository;
    private $suppressionEmailRepository;
    private $suppressionDomainRepository;
    private $suppressionAccountNameRepository;
    private $targetDomainRepository;
    private $targetAccountNameRepository;


    public function __construct(
        Campaign $campaign,
        CampaignTypeRepository $campaignTypeRepository,
        CampaignFileRepository $campaignFileRepository,
        SuppressionEmailRepository $suppressionEmailRepository,
        SuppressionDomainRepository $suppressionDomainRepository,
        SuppressionAccountNameRepository $suppressionAccountNameRepository,
        TargetDomainRepository $targetDomainRepository,
        TargetAccountNameRepository $targetAccountNameRepository
    )
    {
        $this->campaign = $campaign;
        $this->campaignTypeRepository = $campaignTypeRepository;
        $this->campaignFileRepository = $campaignFileRepository;
        $this->suppressionEmailRepository = $suppressionEmailRepository;
        $this->suppressionDomainRepository = $suppressionDomainRepository;
        $this->suppressionAccountNameRepository = $suppressionAccountNameRepository;
        $this->targetDomainRepository = $targetDomainRepository;
        $this->targetAccountNameRepository = $targetAccountNameRepository;
    }

    public function get($filters = array())
    {
        $query = Campaign::query();

        if(isset($filters['campaign_to_assign']) && $filters['campaign_to_assign']) {
            $query->whereNull('parent_id');
        }

        return $query->get();
    }

    public function find($id, $with = array())
    {
        return $this->campaign->findOrFail($id);
    }

    public function store($attributes)
    {
        //dd($attributes);
        $response = array('status' => FALSE, 'message' => 'Something went wrong, please try again.');
        try {
            DB::beginTransaction();
            $campaign = new Campaign();

            if(isset($attributes['parent_id'])) {
                $resultCampaign = $this->find(base64_decode($attributes['parent_id']));
                $campaignId = $resultCampaign->campaign_id;
                $campaign->type  = 'incremental';
                $campaign->parent_id  = base64_decode($attributes['parent_id']);
            } else {
                $lastRecord = Campaign::latest('id')->first();
                if(isset($lastRecord)) {
                    $lastId = str_pad($lastRecord->id + 1,6,"0",STR_PAD_LEFT);
                } else {
                    $lastId = str_pad('1',6,"0",STR_PAD_LEFT);
                }
                $resultCampaignType = $this->campaignTypeRepository->find($attributes['campaign_type_id']);
                $campaignAbbreviation = SiteSetting::where('key', 'Campaign Abbreviation')->first()->value;
                $campaignId = $campaignAbbreviation.$resultCampaignType->name.'-'.$lastId;
            }

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

                //Save Suppression Email
                if(isset($attributes['suppression_email']) && !empty($attributes['suppression_email'])) {
                    $file = $attributes['suppression_email'];
                    $responseSuppressionEmail = $this->suppressionEmailRepository->bulkUpload($campaign->id, $file);
                    if($responseSuppressionEmail['status'] == FALSE) {
                        $responseFlag  = 0;
                        throw new \Exception('Something went wrong, please try again.', 1);
                    }
                    $path = 'public/campaigns/'.$campaign->campaign_id;
                    $result  = $file->storeAs($path, $file->getClientOriginalName());
                    $attributesCampaignFile = array(
                        'campaign_id' => $campaign->id,
                        'file_type' => 'suppression_email',
                        'file_name' => $file->getClientOriginalName(),
                        'extension' => $file->getClientOriginalExtension(),
                    );
                    $responseCampaignFile = $this->campaignFileRepository->store($attributesCampaignFile);
                    if($responseCampaignFile['status'] == FALSE) {
                        throw new \Exception('Something went wrong, please try again.', 1);
                    }
                }
                //Save Suppression Domain
                if(isset($attributes['suppression_domain']) && !empty($attributes['suppression_domain'])) {
                    $file = $attributes['suppression_domain'];
                    $responseSuppressionDomain = $this->suppressionDomainRepository->bulkUpload($campaign->id, $file);
                    if($responseSuppressionDomain['status'] == FALSE) {
                        throw new \Exception('Something went wrong, please try again.', 1);
                    }
                    $path = 'public/campaigns/'.$campaign->campaign_id;
                    $result  = $file->storeAs($path, $file->getClientOriginalName());
                    $attributesCampaignFile = array(
                        'campaign_id' => $campaign->id,
                        'file_type' => 'suppression_domain',
                        'file_name' => $file->getClientOriginalName(),
                        'extension' => $file->getClientOriginalExtension(),
                    );
                    $responseCampaignFile = $this->campaignFileRepository->store($attributesCampaignFile);
                    if($responseCampaignFile['status'] == FALSE) {
                        throw new \Exception('Something went wrong, please try again.', 1);
                    }
                }
                //Save Suppression Account Name
                if(isset($attributes['suppression_account_name']) && !empty($attributes['suppression_account_name'])) {
                    $file = $attributes['suppression_account_name'];
                    $responseSuppressionAccountName = $this->suppressionAccountNameRepository->bulkUpload($campaign->id, $file);
                    if($responseSuppressionAccountName['status'] == FALSE) {
                        throw new \Exception('Something went wrong, please try again.', 1);
                    }
                    $path = 'public/campaigns/'.$campaign->campaign_id;
                    $result  = $file->storeAs($path, $file->getClientOriginalName());
                    $attributesCampaignFile = array(
                        'campaign_id' => $campaign->id,
                        'file_type' => 'suppression_account_name',
                        'file_name' => $file->getClientOriginalName(),
                        'extension' => $file->getClientOriginalExtension(),
                    );
                    $responseCampaignFile = $this->campaignFileRepository->store($attributesCampaignFile);
                    if($responseCampaignFile['status'] == FALSE) {
                        throw new \Exception('Something went wrong, please try again.', 1);
                    }
                }
                //Save Target Domain
                if(isset($attributes['target_domain']) && !empty($attributes['target_domain'])) {
                    $file = $attributes['target_domain'];
                    $responseTargetDomain = $this->targetDomainRepository->bulkUpload($campaign->id, $file);
                    if($responseTargetDomain['status'] == FALSE) {
                        throw new \Exception('Something went wrong, please try again.', 1);
                    }
                    $path = 'public/campaigns/'.$campaign->campaign_id;
                    $result  = $file->storeAs($path, $file->getClientOriginalName());
                    $attributesCampaignFile = array(
                        'campaign_id' => $campaign->id,
                        'file_type' => 'target_domain',
                        'file_name' => $file->getClientOriginalName(),
                        'extension' => $file->getClientOriginalExtension(),
                    );
                    $responseCampaignFile = $this->campaignFileRepository->store($attributesCampaignFile);
                    if($responseCampaignFile['status'] == FALSE) {
                        throw new \Exception('Something went wrong, please try again.', 1);
                    }
                }
                //Save Target Account Name
                if(isset($attributes['target_account_name']) && !empty($attributes['target_account_name'])) {
                    $file = $attributes['target_account_name'];
                    $responseTargetAccountName = $this->targetAccountNameRepository->bulkUpload($campaign->id, $file);
                    if($responseTargetAccountName['status'] == FALSE) {
                        throw new \Exception('Something went wrong, please try again.', 1);
                    }
                    $path = 'public/campaigns/'.$campaign->campaign_id;
                    $result  = $file->storeAs($path, $file->getClientOriginalName());
                    $attributesCampaignFile = array(
                        'campaign_id' => $campaign->id,
                        'file_type' => 'target_account_name',
                        'file_name' => $file->getClientOriginalName(),
                        'extension' => $file->getClientOriginalExtension(),
                    );
                    $responseCampaignFile = $this->campaignFileRepository->store($attributesCampaignFile);
                    if($responseCampaignFile['status'] == FALSE) {
                        throw new \Exception('Something went wrong, please try again.', 1);
                    }
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
            //dd($exception->getMessage());
            $response = array('status' => FALSE, 'message' => 'Something went wrong, please try again.');
        }
        return $response;
    }

    public function update($id, $attributes): array
    {

        //dd($attributes);
        $response = array('status' => FALSE, 'message' => 'Something went wrong, please try again.');
        try {
            DB::beginTransaction();

            if(isset($attributes['id']) && !empty($attributes['id'])) {
                $id = base64_decode($attributes['id']);
            }

            $campaign = $this->find($id);

            if(isset($attributes['name']) && !empty($attributes['name'])) {
                $campaign->name = $attributes['name'];
            }

            if(array_key_exists('v_mail_campaign_id', $attributes)) {
                $campaign->v_mail_campaign_id = $attributes['v_mail_campaign_id'];
            }

            if(isset($attributes['campaign_filter_id']) && !empty($attributes['campaign_filter_id'])) {
                $campaign->campaign_filter_id = $attributes['campaign_filter_id'];
            }

            if(isset($attributes['campaign_type_id']) && !empty($attributes['campaign_type_id'])) {
                $campaign->campaign_type_id = $attributes['campaign_type_id'];
            }

            if(isset($attributes['note'])) {
                $campaign->note = $attributes['note'];
            }

            if(isset($attributes['start_date']) && !empty($attributes['start_date'])) {
                $campaign->start_date = date('Y-m-d', strtotime($attributes['start_date']));
            }

            if(isset($attributes['end_date']) && !empty($attributes['end_date'])) {
                $campaign->end_date = date('Y-m-d', strtotime($attributes['end_date']));
            }

            if(isset($attributes['deliver_count'])) {
                $campaign->deliver_count = $attributes['deliver_count'];
            }

            if(isset($attributes['campaign_status_id']) && $attributes['campaign_status_id'] == 6 && isset($attributes['shortfall_count'])) {
                $campaign->shortfall_count = $attributes['shortfall_count'];
            } else {
                $campaign->shortfall_count = 0;
            }

            if(isset($attributes['allocation'])) {
                $campaign->allocation = $attributes['allocation'];
            }

            if(isset($attributes['campaign_status_id'])) {
                $campaign->campaign_status_id = $attributes['campaign_status_id'];
            }

            if(isset($attributes['pacing'])) {
                $campaign->pacing = $attributes['pacing'];
            }

            $campaign->save();

            if($campaign->id) {

                //Save Campaign's Countries
                if(isset($attributes['country_id'])) {
                    CampaignCountry::whereCampaignId($campaign->id)->delete();
                    $insertCampaignCountries = array();
                    foreach ($attributes['country_id'] as $country) {
                        array_push($insertCampaignCountries, ['campaign_id' => $campaign->id, 'country_id' => $country]);
                    }
                    CampaignCountry::insert($insertCampaignCountries);
                }

                //Save Campaign's Specifications
                if(isset($attributes['specifications']) && !empty($attributes['specifications'])) {
                    $insertCampaignSpecifications = array();
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
                if(isset($attributes['sub-allocation'])) {
                    PacingDetail::whereCampaignId($campaign->id)->delete();
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
                $getChanges = $campaign->getChanges();
                if(isset($getChanges['start_date'])) {
                    $resultPacingDetails = PacingDetail::where('date', '<', $getChanges['start_date'])->delete();
                }
                if(isset($getChanges['end_date'])) {
                    $resultPacingDetails = PacingDetail::where('date', '>', $getChanges['end_date'])->delete();
                }
                DB::commit();


                $response = array('status' => TRUE, 'message' => 'Campaign details updated successfully');
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

    public function updateSpecification($id, $attributes = [])
    {
        $response = array('status' => FALSE, 'message' => 'Something went wrong, please try again.');
        try {
            DB::beginTransaction();
            $campaign = $this->find($id);
            //Campaign Specifications
            $insertCampaignSpecifications = array();
            $fileNames = [];
            $path = 'public/campaigns/'.$campaign->campaign_id;
            foreach ($attributes['specifications'] as $file) {

                $extension = $file->getClientOriginalExtension();
                //$filename  = $campaign->campaign_id.'-' . str_shuffle(time()) . '.' . $extension;
                $filename  = $file->getClientOriginalName();
                $result  = $file->storeAs($path, $filename);
                array_push($insertCampaignSpecifications, ['campaign_id' => $campaign->id, 'file_name' => $filename, 'extension' => $extension]);
                array_push($fileNames, $filename);
            }
            //dd($insertCampaignSpecifications, $fileNames);
            //--Campaign Specifications

            if(CampaignSpecification::insert($insertCampaignSpecifications)) {
                $lastInserted = CampaignSpecification::whereIn('file_name', $fileNames)->get();
                $response = array('status' => TRUE, 'message' => 'Campaign specification added successfully', 'data' => $lastInserted);
                DB::commit();
            } else {
                throw new \Exception('Something went wrong, please try again.', 1);
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            //dd($exception->getMessage());
            $response = array('status' => FALSE, 'message' => 'Something went wrong, please try again.');
        }
        return $response;
    }

    public function updateCampaignFile($id, $attributes = array())
    {
        $response = array('status' => FALSE, 'message' => 'Something went wrong, please try again.');
        try {
            DB::beginTransaction();
            $campaign = $this->find($id);
            $responseFlag = 1;
            $lastInserted = array();
            //Save Suppression Email
            if(isset($attributes['suppression_email']) && !empty($attributes['suppression_email'])) {
                $file = $attributes['suppression_email'];
                $responseSuppressionEmail = $this->suppressionEmailRepository->bulkUpload($campaign->id, $file);
                if($responseSuppressionEmail['status'] == FALSE) {
                    $responseFlag  = 0;
                    throw new \Exception('Something went wrong, please try again.', 1);
                }
                $path = 'public/campaigns/'.$campaign->campaign_id;
                $result  = $file->storeAs($path, $file->getClientOriginalName());
                $attributesCampaignFile = array(
                    'campaign_id' => $campaign->id,
                    'file_type' => 'suppression_email',
                    'file_name' => $file->getClientOriginalName(),
                    'extension' => $file->getClientOriginalExtension(),
                );
                $resultCampaignFile = $this->campaignFileRepository->store($attributesCampaignFile);
                if($resultCampaignFile['status'] == FALSE) {
                    $responseFlag  = 0;
                    throw new \Exception('Something went wrong, please try again.', 1);
                } else {
                    $lastInserted['suppression_email'] = CampaignFile::whereCampaignId($attributesCampaignFile['campaign_id'])
                        ->whereFileType($attributesCampaignFile['file_type'])
                        ->whereFileName($attributesCampaignFile['file_name'])
                        ->with('campaign')
                        ->first();
                }
            }

            //Save Suppression Domain
            if(isset($attributes['suppression_domain']) && !empty($attributes['suppression_domain'])) {
                $file = $attributes['suppression_domain'];
                $responseSuppressionDomain = $this->suppressionDomainRepository->bulkUpload($campaign->id, $file);
                if($responseSuppressionDomain['status'] == FALSE) {
                    $responseFlag  = 0;
                    throw new \Exception('Something went wrong, please try again.', 1);
                }
                $path = 'public/campaigns/'.$campaign->campaign_id;
                $result  = $file->storeAs($path, $file->getClientOriginalName());
                $attributesCampaignFile = array(
                    'campaign_id' => $campaign->id,
                    'file_type' => 'suppression_domain',
                    'file_name' => $file->getClientOriginalName(),
                    'extension' => $file->getClientOriginalExtension(),
                );
                $responseCampaignFile = $this->campaignFileRepository->store($attributesCampaignFile);
                if($responseCampaignFile['status'] == FALSE) {
                    $responseFlag  = 0;
                    throw new \Exception('Something went wrong, please try again.', 1);
                } else {
                    $lastInserted['suppression_domain'] = CampaignFile::whereCampaignId($attributesCampaignFile['campaign_id'])
                        ->whereFileType($attributesCampaignFile['file_type'])
                        ->whereFileName($attributesCampaignFile['file_name'])
                        ->with('campaign')
                        ->first();
                }
            }

            //Save Suppression Account Name
            if(isset($attributes['suppression_account_name']) && !empty($attributes['suppression_account_name'])) {
                $file = $attributes['suppression_account_name'];
                $responseSuppressionAccountName = $this->suppressionAccountNameRepository->bulkUpload($campaign->id, $file);
                if($responseSuppressionAccountName['status'] == FALSE) {
                    $responseFlag  = 0;
                    throw new \Exception('Something went wrong, please try again.', 1);
                }
                $path = 'public/campaigns/'.$campaign->campaign_id;
                $result  = $file->storeAs($path, $file->getClientOriginalName());
                $attributesCampaignFile = array(
                    'campaign_id' => $campaign->id,
                    'file_type' => 'suppression_account_name',
                    'file_name' => $file->getClientOriginalName(),
                    'extension' => $file->getClientOriginalExtension(),
                );
                $responseCampaignFile = $this->campaignFileRepository->store($attributesCampaignFile);
                if($responseCampaignFile['status'] == FALSE) {
                    $responseFlag  = 0;
                    throw new \Exception('Something went wrong, please try again.', 1);
                } else {
                    $lastInserted['suppression_account_name'] = CampaignFile::whereCampaignId($attributesCampaignFile['campaign_id'])
                        ->whereFileType($attributesCampaignFile['file_type'])
                        ->whereFileName($attributesCampaignFile['file_name'])
                        ->with('campaign')
                        ->first();
                }
            }

            //Save Target Domain
            if(isset($attributes['target_domain']) && !empty($attributes['target_domain'])) {
                $file = $attributes['target_domain'];
                $responseTargetDomain = $this->targetDomainRepository->bulkUpload($campaign->id, $file);
                if($responseTargetDomain['status'] == FALSE) {
                    $responseFlag  = 0;
                    throw new \Exception('Something went wrong, please try again.', 1);
                }
                $path = 'public/campaigns/'.$campaign->campaign_id;
                $result  = $file->storeAs($path, $file->getClientOriginalName());
                $attributesCampaignFile = array(
                    'campaign_id' => $campaign->id,
                    'file_type' => 'target_domain',
                    'file_name' => $file->getClientOriginalName(),
                    'extension' => $file->getClientOriginalExtension(),
                );
                $responseCampaignFile = $this->campaignFileRepository->store($attributesCampaignFile);
                if($responseCampaignFile['status'] == FALSE) {
                    $responseFlag  = 0;
                    throw new \Exception('Something went wrong, please try again.', 1);
                } else {
                    $lastInserted['target_domain'] = CampaignFile::whereCampaignId($attributesCampaignFile['campaign_id'])
                        ->whereFileType($attributesCampaignFile['file_type'])
                        ->whereFileName($attributesCampaignFile['file_name'])
                        ->with('campaign')
                        ->first();
                }
            }

            //Save Target Account Name
            if(isset($attributes['target_account_name']) && !empty($attributes['target_account_name'])) {
                $file = $attributes['target_account_name'];
                $responseTargetAccountName = $this->targetAccountNameRepository->bulkUpload($campaign->id, $file);
                if($responseTargetAccountName['status'] == FALSE) {
                    $responseFlag  = 0;
                    throw new \Exception('Something went wrong, please try again.', 1);
                }
                $path = 'public/campaigns/'.$campaign->campaign_id;
                $result  = $file->storeAs($path, $file->getClientOriginalName());
                $attributesCampaignFile = array(
                    'campaign_id' => $campaign->id,
                    'file_type' => 'target_account_name',
                    'file_name' => $file->getClientOriginalName(),
                    'extension' => $file->getClientOriginalExtension(),
                );
                $responseCampaignFile = $this->campaignFileRepository->store($attributesCampaignFile);
                if($responseCampaignFile['status'] == FALSE) {
                    $responseFlag  = 0;
                    throw new \Exception('Something went wrong, please try again.', 1);
                } else {
                    $lastInserted['target_account_name'] = CampaignFile::whereCampaignId($attributesCampaignFile['campaign_id'])
                        ->whereFileType($attributesCampaignFile['file_type'])
                        ->whereFileName($attributesCampaignFile['file_name'])
                        ->with('campaign')
                        ->first();
                }
            }

            if($responseFlag) {
                $response = array('status' => TRUE, 'message' => 'Campaign file(s) added successfully', 'data' => $lastInserted);
                DB::commit();
            } else {
                throw new \Exception('Something went wrong, please try again.', 1);
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            //dd($exception->getMessage());
            $response = array('status' => FALSE, 'message' => 'Something went wrong, please try again.');
        }
        return $response;
    }
}
