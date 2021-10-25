<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Campaign extends Model
{
    use SoftDeletes;
    protected $guarded = array();
    public $timestamps = true;

    public function campaignType(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(CampaignType::class, 'id', 'campaign_type_id');
    }

    public function campaignFilter(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(CampaignFilter::class, 'id', 'campaign_filter_id');
    }

    public function countries(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CampaignCountry::class, 'campaign_id', 'id');
    }

    public function specifications(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CampaignSpecification::class, 'campaign_id', 'id');
    }

    public function campaignFiles(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CampaignFile::class, 'campaign_id', 'id');
    }

    public function suppressionEmails(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SuppressionEmail::class, 'campaign_id', 'id');
    }

    public function suppressionDomains(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SuppressionDomain::class, 'campaign_id', 'id');
    }

    public function suppressionAccountNames(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SuppressionAccountName::class, 'campaign_id', 'id');
    }

    public function targetDomains(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TargetDomain::class, 'campaign_id', 'id');
    }

    public function targetAccountNames(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(TargetAccountName::class, 'campaign_id', 'id');
    }

    public function pacingDetails()
    {
        return $this->hasMany(PacingDetail::class, 'campaign_id', 'id');
    }

    public function assigned_ratls()
    {
        return $this->hasMany(CampaignAssignRATL::class, 'campaign_id', 'id');
    }

    public function assigned_agents()
    {
        return $this->hasMany(CampaignAssignAgent::class, 'campaign_id', 'id');
    }

    public function assigned_vendor_managers()
    {
        return $this->hasMany(CampaignAssignVendorManager::class, 'campaign_id', 'id');
    }

    public function assigned_vendors()
    {
        return $this->hasMany(CampaignAssignVendor::class, 'campaign_id', 'id');
    }

    public function assigned_qatls()
    {
        return $this->hasMany(CampaignAssignQATL::class, 'campaign_id', 'id');
    }

    public function assigned_quality_analysts()
    {
        return $this->hasMany(CampaignAssignQualityAnalyst::class, 'campaign_id', 'id');
    }

    public function children()
    {
        return $this->hasMany(Campaign::class, 'parent_id', 'id');
    }

}
