<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CampaignAssignVendorManager extends Model
{
    use SoftDeletes;
    protected $guarded = array();
    public $timestamps = true;
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

    public function vendors()
    {
        return $this->hasMany(CampaignAssignVendor::class, 'campaign_assign_vm_id', 'id');
    }
}
