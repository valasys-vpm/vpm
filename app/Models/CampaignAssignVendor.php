<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CampaignAssignVendor extends Model
{
    use SoftDeletes;
    protected $guarded = array();
    public $timestamps = true;

    public function user()
    {
        return $this->hasOne(Vendor::class, 'id', 'user_id');
    }
}
