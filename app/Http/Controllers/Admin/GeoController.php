<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Region;
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
    
    public function getRegions(Request $request): \Illuminate\Http\JsonResponse
    {
        $filters = array_filter(json_decode($request->get('filters'), true));
        $search_data = $request->get('search');
        $searchValue = $search_data['value'];

        $order = $request->get('order');
        $draw = $request->get('draw');
        $limit = $request->get("length"); // Rows display per page
        $offset = $request->get("start");

        $query = Region::query();
        $totalRecords = $query->count();

        //Search Data
        if(isset($searchValue) && $searchValue != "") {
            $query->where("name", "like", "%$searchValue%");
            $query->orWhere("abbreviation", "like", "%$searchValue%");
        }

        //Filters
        if(!empty($filters)) {

        }
        
        $totalFilterRecords = $query->count();

        //Order By
        $orderColumn = $order[0]['column'];
        $orderDirection = $order[0]['dir'];

        switch ($orderColumn) {
            case '0': $query->orderBy('abbreviation', $orderDirection); break;
            case '1': $query->orderBy('name', $orderDirection); break;
            case '2': $query->orderBy('status', $orderDirection); break;
            default: $query->orderBy('name'); break;
        }

        $query->offset($offset);
        $query->limit($limit);
        $result = $query->get();

        $ajaxData = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalFilterRecords,
            "aaData" => $result
        );

        return response()->json($ajaxData);
    }

}
