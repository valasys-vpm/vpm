<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    private $data;

    public function __construct()
    {
        $this->data = array();
    }

    public function index()
    {
        $this->data['resultUsers'] = [];
        $this->data['resultDepartments'] = [];
        $this->data['resultDesignations'] = [];
        $this->data['resultRoles'] = [];
        return view('manager.campaign.list', $this->data);
    }
}
