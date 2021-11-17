<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CampaignAssignAgent extends Model
{
    use SoftDeletes;
    protected $guarded = array();
    public $timestamps = true;

    protected $appends = ['agent_lead_count'];

    public function campaign()
    {
        return $this->hasOne(Campaign::class, 'id', 'campaign_id');
    }
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function userAssignedBy()
    {
        return $this->hasOne(User::class, 'id', 'assigned_by');
    }

    public function caratl()
    {
        return $this->hasOne(CampaignAssignRATL::class, 'id', 'campaign_assign_ratl_id');
    }

    public function agentLeads()
    {
        return $this->hasMany(AgentLead::class, 'ca_agent_id', 'id');
    }

    public function getAgentLeadCountAttribute()
    {
        return $this->agentLeads->count();
    }
}
