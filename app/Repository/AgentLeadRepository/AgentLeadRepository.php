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
            $agentLead->employee_size = $attributes['employee_size'];
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

            $agentLead->save();
            if($agentLead->id) {
                DB::commit();
                $response = array('status' => TRUE, 'message' => 'Lead added successfully');
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
