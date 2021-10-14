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

    public function pacingDetails()
    {
        return $this->hasMany(PacingDetail::class, 'campaign_id', 'id');
    }

    public function children()
    {
        return $this->hasMany(Campaign::class, 'parent_id', 'id');
    }

}
