<?php

namespace App\Repository\AgentLeadRepository;

use App\Models\AgentLead;
use App\Models\CampaignAssignAgent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AgentLeadRepository implements AgentLeadInterface
{
    private $agentLead;

    public function __construct(
        AgentLead $agentLead
    )
    {
        $this->agentLead = $agentLead;
    }

    public function get($filters = array())
    {
        $query = AgentLead::query();

        if (isset($filters['ca_agent_id']) && $filters['ca_agent_id']) {
            $query->where('ca_agent_id', $filters['ca_agent_id']);
        }

        return $query->get();
    }

    public function find($id, $with = array())
    {
        $query = AgentLead::query();

        return $query->findOrFail($id);
    }

    public function store($attributes)
    {
        $response = array('status' => FALSE, 'message' => 'Something went wrong, please try again.');
        try {
            DB::beginTransaction();
            $agentLead = new AgentLead();
            $resultCAAgent = CampaignAssignAgent::findOrFail(base64_decode($attributes['ca_agent_id']));
            $agentLead->ca_agent_id = $resultCAAgent->id;
            $agentLead->campaign_id = $resultCAAgent->campaign_id;
            $agentLead->agent_id = Auth::id();
            $agentLead->first_name = $attributes['first_name'];
            $agentLead->last_name = $attributes['last_name'];
            $agentLead->company_name = $attributes['company_name'];
            $agentLead->email_address = $attributes['email_address'];
            $agentLead->specific_title = $attributes['specific_title'];
            if(isset($attributes['job_level']) && !empty(trim($attributes['job_level']))) {
                $agentLead->job_level = $attributes['job_level'];
            }
            if(isset($attributes['job_role']) && !empty(trim($attributes['job_role']))) {
                $agentLead->job_role = $attributes['job_role'];
            }
            $agentLead->phone_number = $attributes['phone_number'];
            $agentLead->address_1 = $attributes['address_1'];
            if(isset($attributes['address_2']) && !empty(trim($attributes['address_2']))) {
                $agentLead->address_2 = $attributes['address_2'];
            }
            $agentLead->city = $attributes['city'];
            $agentLead->state = $attributes['state'];
            $agentLead->zipcode = $attributes['zipcode'];
            $agentLead->country = $attributes['country'];
            $agentLead->industry = $attributes['industry'];
            $agentLead->employee_size = $attributes['employee_size'];
            if(isset($attributes['employee_size_2']) && !empty(trim($attributes['employee_size_2']))) {
                $agentLead->employee_size_2 = $attributes['employee_size_2'];
            }
            $agentLead->revenue = $attributes['revenue'];
            $agentLead->company_domain = $attributes['company_domain'];
            if(isset($attributes['website']) && !empty(trim($attributes['website']))) {
                $agentLead->website = $attributes['website'];
            }
            $agentLead->company_linkedin_url = $attributes['company_linkedin_url'];
            $agentLead->linkedin_profile_link = $attributes['linkedin_profile_link'];

            if(isset($attributes['linkedin_profile_sn_link']) && !empty(trim($attributes['linkedin_profile_sn_link']))) {
                $agentLead->linkedin_profile_sn_link = $attributes['linkedin_profile_sn_link'];
            }

            if(isset($attributes['comment']) && !empty(trim($attributes['comment']))) {
                $agentLead->comment = $attributes['comment'];
            }

            if(isset($attributes['comment_2']) && !empty(trim($attributes['comment_2']))) {
                $agentLead->comment_2 = $attributes['comment_2'];
            }

            if(isset($attributes['qc_comment']) && !empty(trim($attributes['qc_comment']))) {
                $agentLead->qc_comment = $attributes['qc_comment'];
            }

            $agentLead->save();
            if($agentLead->id) {
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
        $response = array('status' => FALSE, 'message' => 'Something went wrong, please try again.');
        try {
            DB::beginTransaction();
            $agentLead = AgentLead::findOrFail($id);

            if(isset($attributes['ca_agent_id']) && !empty($attributes['ca_agent_id'])) {
                $agentLead->ca_agent_id = $attributes['ca_agent_id'];
            }

            if(isset($attributes['campaign_id']) && !empty($attributes['campaign_id'])) {
                $agentLead->campaign_id = $attributes['campaign_id'];
            }

            if(isset($attributes['agent_id']) && !empty($attributes['agent_id'])) {
                $agentLead->agent_id = $attributes['agent_id'];
            }

            if(isset($attributes['first_name']) && !empty($attributes['first_name'])) {
                $agentLead->first_name = $attributes['first_name'];
            }

            if(isset($attributes['last_name']) && !empty($attributes['last_name'])) {
                $agentLead->last_name = $attributes['last_name'];
            }

            if(isset($attributes['company_name']) && !empty($attributes['company_name'])) {
                $agentLead->company_name = $attributes['company_name'];
            }

            if(isset($attributes['email_address']) && !empty($attributes['email_address'])) {
                $agentLead->email_address = $attributes['email_address'];
            }

            if(isset($attributes['specific_title']) && !empty($attributes['specific_title'])) {
                $agentLead->specific_title = $attributes['specific_title'];
            }

            if(isset($attributes['job_level']) && !empty(trim($attributes['job_level']))) {
                $agentLead->job_level = $attributes['job_level'];
            }

            if(isset($attributes['job_role']) && !empty(trim($attributes['job_role']))) {
                $agentLead->job_role = $attributes['job_role'];
            }

            if(isset($attributes['phone_number']) && !empty($attributes['phone_number'])) {
                $agentLead->phone_number = $attributes['phone_number'];
            }

            if(isset($attributes['address_1']) && !empty($attributes['address_1'])) {
                $agentLead->address_1 = $attributes['address_1'];
            }

            if(isset($attributes['address_2']) && !empty(trim($attributes['address_2']))) {
                $agentLead->address_2 = $attributes['address_2'];
            }

            if(isset($attributes['city']) && !empty($attributes['city'])) {
                $agentLead->city = $attributes['city'];
            }

            if(isset($attributes['state']) && !empty($attributes['state'])) {
                $agentLead->state = $attributes['state'];
            }

            if(isset($attributes['zipcode']) && !empty($attributes['zipcode'])) {
                $agentLead->zipcode = $attributes['zipcode'];
            }

            if(isset($attributes['country']) && !empty($attributes['country'])) {
                $agentLead->country = $attributes['country'];
            }

            if(isset($attributes['industry']) && !empty($attributes['industry'])) {
                $agentLead->industry = $attributes['industry'];
            }

            if(isset($attributes['employee_size']) && !empty($attributes['employee_size'])) {
                $agentLead->employee_size = $attributes['employee_size'];
            }

            if(isset($attributes['employee_size_2']) && !empty($attributes['employee_size_2'])) {
                $agentLead->employee_size_2 = $attributes['employee_size_2'];
            }

            if(isset($attributes['revenue']) && !empty($attributes['revenue'])) {
                $agentLead->revenue = $attributes['revenue'];
            }

            if(isset($attributes['company_domain']) && !empty($attributes['company_domain'])) {
                $agentLead->company_domain = $attributes['company_domain'];
            }

            if(isset($attributes['website']) && !empty(trim($attributes['website']))) {
                $agentLead->website = $attributes['website'];
            }

            if(isset($attributes['company_linkedin_url']) && !empty($attributes['company_linkedin_url'])) {
                $agentLead->company_linkedin_url = $attributes['company_linkedin_url'];
            }

            if(isset($attributes['linkedin_profile_link']) && !empty($attributes['linkedin_profile_link'])) {
                $agentLead->linkedin_profile_link = $attributes['linkedin_profile_link'];
            }

            if(isset($attributes['linkedin_profile_sn_link']) && !empty(trim($attributes['linkedin_profile_sn_link']))) {
                $agentLead->linkedin_profile_sn_link = $attributes['linkedin_profile_sn_link'];
            }

            if(isset($attributes['comment']) && !empty(trim($attributes['comment']))) {
                $agentLead->comment = $attributes['comment'];
            }

            if(isset($attributes['comment_2']) && !empty(trim($attributes['comment_2']))) {
                $agentLead->comment_2 = $attributes['comment_2'];
            }

            if(isset($attributes['qc_comment']) && !empty(trim($attributes['qc_comment']))) {
                $agentLead->qc_comment = $attributes['qc_comment'];
            }

            if(array_key_exists('status', $attributes)) {
                $agentLead->status = $attributes['status'];
            }

            if($agentLead->save()) {
                DB::commit();
                $response = array('status' => TRUE, 'message' => 'Lead details updated successfully', 'details' => $agentLead);
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
