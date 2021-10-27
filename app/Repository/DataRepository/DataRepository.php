<?php

namespace App\Repository\DataRepository;

use App\Models\AgentData;
use App\Models\CampaignAssignAgent;
use App\Models\Data;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Excel;

class DataRepository implements DataInterface
{
    /**
     * @var Data
     */
    private $data;

    public function __construct(Data $data)
    {

        $this->data = $data;
    }

    public function get($filters = array(), $suppressionList = array(), $targetList = array())
    {
        $query = Data::query();

        if(isset($filters['job_level']) && !empty($filters['job_level'])) {
            $query->whereIn('job_level', $filters['job_level']);
        }

        if(isset($filters['job_role']) && !empty($filters['job_role'])) {
            $query->whereIn('job_role', $filters['job_role']);
        }

        if(isset($filters['employee_size']) && !empty($filters['employee_size'])) {
            $query->whereIn('employee_size', $filters['employee_size']);
        }

        if(isset($filters['revenue']) && !empty($filters['revenue'])) {
            $query->whereIn('revenue', $filters['revenue']);
        }

        if(isset($filters['country']) && !empty($filters['country'])) {
            $query->whereIn('country', $filters['country']);
        }

        if(isset($filters['state']) && !empty($filters['state'])) {
            $query->whereIn('state', $filters['state']);
        }

        if(!empty($suppressionList)) {
            if(isset($suppressionList['suppression_email']) && !empty($suppressionList['suppression_email']) && $suppressionList['suppression_email']->count()) {
                $query->whereNotIn('email_address', $suppressionList['suppression_email']->pluck('email')->toArray());
            }

            if(isset($suppressionList['suppression_domain']) && !empty($suppressionList['suppression_domain']) && $suppressionList['suppression_domain']->count()) {
                $query->whereNotIn('company_domain', $suppressionList['suppression_domain']->pluck('domain')->toArray());
            }

            if(isset($suppressionList['suppression_account_name']) && !empty($suppressionList['suppression_account_name']) && $suppressionList['suppression_account_name']->count()) {
                $query->whereNotIn('company_name', $suppressionList['suppression_account_name']->pluck('account_name')->toArray());
            }
        }

        if(!empty($targetList)) {
            if(isset($targetList['target_domain']) && !empty($targetList['target_domain']) && $targetList['target_domain']->count()) {
                $query->whereIn('company_domain', $targetList['target_domain']->pluck('domain')->toArray());
            }
        }

        if(isset($filters['ca_ratl_id']) && !empty($filters['ca_ratl_id'])) {
            //Get Data Ids Already Assigned and live
            $resultCAAgents = CampaignAssignAgent::where('campaign_assign_ratl_id', base64_decode($filters['ca_ratl_id']))->get();
            $ca_agent_ids = $resultCAAgents->pluck('id')->toArray();

            $resultAgentData = AgentData::whereIn('ca_agent_id', $ca_agent_ids);
            $agent_data_ids = $resultAgentData->pluck('data_id')->toArray();

            $query->whereNotIn('id', $agent_data_ids);
        }


        if(isset($filters['limit']) && !empty($filters['limit'])) {
            $query->limit($filters['limit']);
        }

        $query->inRandomOrder();
        $query->orderBy('first_name');
        //dd($query->get()->toArray());
        return $query->get();
    }

    public function find($id)
    {
        $query = Data::query();
        return $query->findOrFail($id);
    }

    public function store($attributes)
    {
        $response = array('status' => FALSE, 'message' => 'Something went wrong, please try again.');
        try {
            DB::beginTransaction();

            //$campaign->save();

            if(0) {
                DB::commit();
                $response = array('status' => TRUE, 'message' => 'Data added successfully');
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

    public function update($id, $attributes)
    {
        // TODO: Implement update() method.
    }

    public function destroy($id)
    {
        // TODO: Implement destroy() method.
    }

    public function import($file): array
    {
        $response = array('status' => FALSE, 'message' => 'Something went wrong, please try again.');
        try {
            DB::beginTransaction();
            ini_set('memory_limit', '-1');
            ini_set('max_execution_time', '0');
            $excelData = Excel::toArray('', $file);
            //dd($excelData);

            array_shift($excelData[0]);
            $totalDataCount = count($excelData[0]);

            if($totalDataCount < 25000) {
                $failedData = array();
                $validData = array();
                $updated_by = Auth::id();
                foreach ($excelData[0] as $key => $row) {
                    $leadData = array(
                        'first_name' => trim($row[0]),
                        'last_name' => trim($row[1]),
                        'company_name' => trim($row[2]),
                        'email_address' => trim($row[3]),
                        'specific_title' => trim($row[4]),
                        'job_level' => trim($row[5]),
                        'job_role' => trim($row[6]),
                        'phone_number' => trim($row[7]),
                        'address_1' => trim($row[8]),
                        'address_2' => trim($row[9]),
                        'city' => trim($row[10]),
                        'state' => trim($row[11]),
                        'zipcode' => trim($row[12]),
                        'country' => trim($row[13]),
                        'employee_size' => trim($row[14]),
                        'revenue' => trim($row[15]),
                        'company_domain' => trim($row[16]),
                        'website' => trim($row[17]),
                        'company_linkedin_url' => trim($row[18]),
                        'linkedin_profile_link' => trim($row[19]),
                        'linkedin_profile_sn_link' => trim($row[20]),
                        'updated_by' => $updated_by,
                        'status' => 1,
                    );
                    $resultValidate = $this->validateLeadData($leadData);
                    if(empty($resultValidate)) {
                        $validData[] = $leadData;
                        /*
                        $data = new Data();
                        $data->first_name = trim($row[0]);
                        $data->last_name = trim($row[1]);
                        $data->company_name = trim($row[2]);

                        $data->email_address = trim($row[3]);

                        $data->specific_title = trim($row[4]);
                        if(!empty(trim($row[5]))) {
                            $data->job_level = trim($row[5]);
                        }
                        if(!empty(trim($row[6]))) {
                            $data->job_role = trim($row[6]);
                        }
                        $data->phone_number = trim($row[7]);
                        $data->address_1 = trim($row[8]);
                        if(!empty(trim($row[9]))) {
                            $data->address_2 = trim($row[9]);
                        }
                        $data->city = trim($row[10]);
                        $data->state = trim($row[11]);
                        $data->zipcode = trim($row[12]);
                        $data->country = trim($row[13]);
                        $data->employee_size = trim($row[14]);
                        $data->revenue = trim($row[15]);
                        $data->company_domain = trim($row[16]);
                        if(!empty(trim($row[17]))) {
                            $data->website = trim($row[17]);
                        }
                        if(!empty(trim($row[18]))) {
                            $data->company_linkedin_url = trim($row[18]);
                        }
                        $data->linkedin_profile_link = trim($row[19]);
                        $data->linkedin_profile_sn_link = trim($row[20]);
                        $data->updated_by  = Auth::id();
                        $data->status  = 1;
                        $data->save();
                        if($data->id) {
                            $successCount++;
                        }
                        */
                    } else {
                        $failedData[] = $leadData;
                    }
                }

                //dd($validData);

                if(count($validData)) {
                    DB::disableQueryLog();
                    $finalData = array_chunk($validData, 2500);
                    foreach ($finalData as $key => $chunk) {
                        if(!DB::table('data')->insert($chunk)) {
                            throw new \Exception('Something went wrong, please try again.', 1);
                        }
                        //Data::insert($validData);
                    }
                    DB::commit();
                    $response = array('status' => TRUE, 'message' => 'Data imported successfully', 'data' => ['success_count' => count($validData), 'failed_data' => $failedData]);
                } else {
                    $response = array('status' => FALSE, 'message' => 'Please check data and try again.', 'data' => ['failed_data' => $failedData]);
                }
            } else {
                $response = array('status' => FALSE, 'message' => 'Max row limit 25000, please check data.');
            }


        } catch (\Exception $exception) {
            DB::rollBack();
            dd($exception->getMessage());
            $response = array('status' => FALSE, 'message' => 'Something went wrong, please try again.');
        }
        return $response;
    }

    public function validateLeadData($leadData)
    {
        $response = array();

        //Check Duplicate entry
        if(isset($leadData['email_address']) && !empty($leadData['email_address'])) {
            $resultData = Data::whereEmailAddress($leadData['email_address'])->first();
            if(!empty($resultData)) {
                $response['email_address'] = 'Email Id already exists';
            }
        } else {
            $response['email_address'] = 'Email Id already exists';
        }

        return $response;
    }
}
