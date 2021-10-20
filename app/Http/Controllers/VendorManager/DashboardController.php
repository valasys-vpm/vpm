<?php

namespace App\Http\Controllers\VendorManager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    private $data;

    public function __construct()
    {
        $this->data = array();
    }

    public function index()
    {
        return view('vendor_manager.dashboard');
    }
}
