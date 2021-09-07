<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GeoController extends Controller
{
    private $data;
   

    public function __construct()
    {
        $this->data = array();
       
    }

    public function regionIndex ()
    {
        return view('admin.geo.region.list');
    }


}
