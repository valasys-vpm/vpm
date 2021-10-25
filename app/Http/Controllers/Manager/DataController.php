<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Data;
use Illuminate\Http\Request;

class DataController extends Controller
{
    private $data;

    public function __construct()
    {
        $this->data = array();
    }

    public function index()
    {
        return view('manager.data.list', $this->data);
    }

    public function getData(Request $request): \Illuminate\Http\JsonResponse
    {
        $filters = array_filter(json_decode($request->get('filters'), true));
        $search_data = $request->get('search');
        $searchValue = $search_data['value'];
        $order = $request->get('order');
        $draw = $request->get('draw');
        $limit = $request->get("length"); // Rows display per page
        $offset = $request->get("start");

        $query = Data::query();
        $totalRecords = $query->count();

        //Search Data
        if(isset($searchValue) && $searchValue != "") {
            $query->where("first_name", "like", "%$searchValue%");
        }
        //Filters
        if(!empty($filters)) {

        }


        //Order By
        $orderColumn = $order[0]['column'];
        $orderDirection = $order[0]['dir'];
        switch ($orderColumn) {
            case '0': $query->orderBy('first_name', $orderDirection); break;
            case '1': $query->orderBy('first_name', $orderDirection); break;
            case '2': $query->orderBy('first_name', $orderDirection); break;
            case '3': $query->orderBy('first_name', $orderDirection); break;
            case '4': $query->orderBy('first_name', $orderDirection); break;
            default: $query->orderBy('first_name'); break;
        }

        $totalFilterRecords = $query->count();
        if($limit > 0) {
            $query->offset($offset);
            $query->limit($limit);
        }

        $result = $query->get();

        //dd($result->toArray());

        $ajaxData = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalFilterRecords,
            "aaData" => $result
        );

        return response()->json($ajaxData);
    }
}
