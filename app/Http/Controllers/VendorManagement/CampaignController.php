<?php

namespace App\Http\Controllers\VendorManagement;

use App\Http\Controllers\Controller;
use App\Repository\VendorRepository\VendorRepository;
use Illuminate\Http\Request;
use App\Models\Vendor;
use Auth;


class CampaignController extends Controller
{
    private $data;
    private $vendorRepository;

    public function __construct(VendorRepository $vendorRepository)
    {
        $this->data = array();
        $this->vendorRepository = $vendorRepository;
    }

    public function index()
    {
        $this->data['resultVendors'] = $this->vendorRepository->get(array('status' => 1));
        //dd($this->data['resultVendors']->toArray());

        return view('vendor_management.campaign.list',$this->data);
    }
}
