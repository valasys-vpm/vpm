<?php

namespace App\Repository\VendorLead;

use App\Models\CampaignAssignVendor;
use App\Models\CampaignAssignVendorManager;
use App\Models\VendorLead;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Excel;

class VendorLeadRepository implements VendorLeadInterface
{

    public function get($filters = array())
    {
        $query = VendorLead::query();

        return $query->get();
    }

    public function find($id)
    {
        $query = VendorLead::query();

        return $query->findOrFail($id);
    }

    public function store($attributes)
    {
        $response = array('status' => FALSE, 'message' => 'Something went wrong, please try again.');
        try {
            DB::beginTransaction();
            $vendorLead = new VendorLead();
            $vendorLead->ca_vendor_id = $attributes['ca_vendor_id'];
            $vendorLead->campaign_id = $attributes['campaign_id'];
            $vendorLead->vendor_id = $attributes['vendor_id'];
            $vendorLead->first_name = $attributes['first_name'];
            $vendorLead->last_name = $attributes['last_name'];
            $vendorLead->company_name = $attributes['company_name'];
            $vendorLead->email_address = $attributes['email_address'];
            $vendorLead->specific_title = $attributes['specific_title'];
            if(isset($attributes['job_level']) && !empty(trim($attributes['job_level']))) {
                $vendorLead->job_level = $attributes['job_level'];
            }
            if(isset($attributes['job_role']) && !empty(trim($attributes['job_role']))) {
                $vendorLead->job_role = $attributes['job_role'];
            }
            $vendorLead->phone_number = $attributes['phone_number'];
            $vendorLead->address_1 = $attributes['address_1'];
            if(isset($attributes['address_2']) && !empty(trim($attributes['address_2']))) {
                $vendorLead->address_2 = $attributes['address_2'];
            }
            $vendorLead->city = $attributes['city'];
            $vendorLead->state = $attributes['state'];
            $vendorLead->zipcode = $attributes['zipcode'];
            $vendorLead->country = $attributes['country'];
            $vendorLead->industry = $attributes['industry'];
            $vendorLead->employee_size = $attributes['employee_size'];
            if(isset($attributes['employee_size_2']) && !empty(trim($attributes['employee_size_2']))) {
                $vendorLead->employee_size_2 = $attributes['employee_size_2'];
            }
            $vendorLead->revenue = $attributes['revenue'];
            $vendorLead->company_domain = $attributes['company_domain'];
            if(isset($attributes['website']) && !empty(trim($attributes['website']))) {
                $vendorLead->website = $attributes['website'];
            }
            $vendorLead->company_linkedin_url = $attributes['company_linkedin_url'];
            $vendorLead->linkedin_profile_link = $attributes['linkedin_profile_link'];

            if(isset($attributes['linkedin_profile_sn_link']) && !empty(trim($attributes['linkedin_profile_sn_link']))) {
                $vendorLead->linkedin_profile_sn_link = $attributes['linkedin_profile_sn_link'];
            }

            if(isset($attributes['comment']) && !empty(trim($attributes['comment']))) {
                $vendorLead->comment = $attributes['comment'];
            }

            $vendorLead->save();
            if($vendorLead->id) {
                DB::commit();
                $response = array('status' => TRUE, 'message' => 'Lead added successfully');
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
        // TODO: Implement update() method.
    }

    public function destroy($id)
    {
        // TODO: Implement destroy() method.
    }

    public function uploadLeads($attributes)
    {
        $response = array('status' => FALSE, 'message' => 'Something went wrong, please try again.');
        try {
            $resultCAVendor = CampaignAssignVendor::find($attributes['vendor_id']);

            if(isset($attributes['lead_file']) && !empty($attributes['lead_file'])) {
                $lead_file = $attributes['lead_file'];
            }
            ini_set('memory_limit', '-1');
            ini_set('max_execution_time', '0');

            $excelData = Excel::toArray('', $attributes['lead_file']);
            array_shift($excelData[0]);

            $successCount = 0;

            foreach ($excelData[0] as $key => $row) {
                $data['ca_vendor_id'] = $resultCAVendor->id;
                $data['campaign_id'] = $resultCAVendor->campaign_id;
                $data['vendor_id'] = $resultCAVendor->user_id;
                $data['first_name'] = $row[0];
                $data['last_name'] = $row[1];
                $data['company_name'] = $row[2];
                $data['email_address'] = $row[3];
                $data['specific_title'] = $row[4];
                $data['job_level'] = $row[5];
                $data['job_role'] = $row[6];
                $data['phone_number'] = $row[7];
                $data['address_1'] = $row[8];
                $data['address_2'] = $row[9];
                $data['city'] = $row[10];
                $data['state'] = $row[11];
                $data['zipcode'] = $row[12];
                $data['country'] = $row[13];
                $data['industry'] = $row[14];
                $data['employee_size'] = $row[15];
                $data['employee_size_2'] = $row[16];
                $data['revenue'] = $row[17];
                $data['company_domain'] = $row[18];
                $data['website'] = $row[19];
                $data['company_linkedin_url'] = $row[20];
                $data['linkedin_profile_link'] = $row[21];
                $data['linkedin_profile_sn_link'] = $row[22];
                $data['comment'] = $row[23];
                /*
                $data['vm_comment'] = $row;
                $data['qc_comment'] = $row;
                $data['status'] = $row;
                */

                $responseStore = $this->store($data);

                if($responseStore['status'] == TRUE) {
                    $successCount++;
                }

            }

            $response = array('status' => TRUE, 'message' => $successCount.' leads uploaded successfully');

        } catch (\Exception $exception) {
            DB::rollBack();
            $response = array('status' => FALSE, 'message' => 'Something went wrong, please try again.');
        }
        return $response;
    }
}
