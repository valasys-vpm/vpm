<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Repository\CampaignFilterRepository\CampaignFilterRepository;
use App\Repository\CampaignRepository\CampaignRepository;
use App\Repository\CampaignSpecificationRepository\CampaignSpecificationRepository;
use App\Repository\CampaignStatusRepository\CampaignStatusRepository;
use App\Repository\CampaignTypeRepository\CampaignTypeRepository;
use App\Repository\CountryRepository\CountryRepository;
use App\Repository\HolidayRepository\HolidayRepository;
use App\Repository\PacingDetailRepository\PacingDetailRepository;
use App\Repository\RegionRepository\RegionRepository;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    private $data;
    private $campaignStatusRepository;
    private $campaignFilterRepository;
    private $campaignTypeRepository;
    private $countryRepository;
    private $regionRepository;
    private $holidayRepository;
    private $campaignRepository;
    private $campaignSpecificationRepository;
    private $pacingDetailRepository;

    public function __construct(
        CampaignStatusRepository $campaignStatusRepository,
        CampaignFilterRepository $campaignFilterRepository,
        CampaignTypeRepository $campaignTypeRepository,
        CountryRepository $countryRepository,
        RegionRepository $regionRepository,
        HolidayRepository $holidayRepository,
        CampaignRepository $campaignRepository,
        CampaignSpecificationRepository $campaignSpecificationRepository,
        PacingDetailRepository $pacingDetailRepository
    )
    {
        $this->data = array();
        $this->campaignStatusRepository = $campaignStatusRepository;
        $this->campaignFilterRepository = $campaignFilterRepository;
        $this->campaignTypeRepository =$campaignTypeRepository;
        $this->countryRepository = $countryRepository;
        $this->regionRepository = $regionRepository;
        $this->holidayRepository = $holidayRepository;
        $this->campaignRepository = $campaignRepository;
        $this->campaignSpecificationRepository = $campaignSpecificationRepository;
        $this->pacingDetailRepository = $pacingDetailRepository;
    }

    public function index()
    {
        return view('manager.campaign.list', $this->data);
    }

    public function show($id)
    {
        try {
            $this->data['resultCampaignStatuses'] = $this->campaignStatusRepository->get(array('status' => 1));
            $this->data['resultCampaignFilters'] = $this->campaignFilterRepository->get(array('status' => 1));
            $this->data['resultCampaignTypes'] = $this->campaignTypeRepository->get(array('status' => 1));
            $this->data['resultCountries'] = $this->countryRepository->get(array('status' => 1));
            $this->data['resultRegions'] = $this->regionRepository->get(array('status' => 1));
            $this->data['resultCampaign'] = $this->campaignRepository->find(base64_decode($id));
            //dd($this->data['resultCampaign']->campaignFiles->toArray());
            return view('manager.campaign.show', $this->data);
        } catch (\Exception $exception) {
            return redirect()->route('manager.campaign.list')->with('error', ['title' => 'Error while processing request', 'message' => 'Campaign details not found']);
        }
    }

    public function create()
    {
        $this->data['resultCampaignStatuses'] = $this->campaignStatusRepository->get(array('status' => 1));
        $this->data['resultCampaignFilters'] = $this->campaignFilterRepository->get(array('status' => 1));
        $this->data['resultCampaignTypes'] = $this->campaignTypeRepository->get(array('status' => 1));
        $this->data['resultCountries'] = $this->countryRepository->get(array('status' => 1));
        $this->data['resultRegions'] = $this->regionRepository->get(array('status' => 1));
        $this->data['resultHolidays'] = $this->holidayRepository->get(array('status' => 1));
        return view('manager.campaign.create', $this->data);
    }

    public function createIncremental($id)
    {
        $this->data['resultCampaign'] = $this->campaignRepository->find(base64_decode($id));
        $this->data['resultCampaignStatuses'] = $this->campaignStatusRepository->get(array('status' => 1));
        $this->data['resultCampaignFilters'] = $this->campaignFilterRepository->get(array('status' => 1));
        $this->data['resultCampaignTypes'] = $this->campaignTypeRepository->get(array('status' => 1));
        $this->data['resultCountries'] = $this->countryRepository->get(array('status' => 1));
        $this->data['resultRegions'] = $this->regionRepository->get(array('status' => 1));
        $this->data['resultHolidays'] = $this->holidayRepository->get(array('status' => 1));
        return view('manager.campaign.incremental.create', $this->data);
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $attributes = $request->all();
        $response = $this->campaignRepository->store($attributes);
        if($response['status'] == TRUE) {
            if($request->has('parent_id')) {
                return redirect()->route('manager.campaign.show', $attributes['parent_id'])->with('success', ['title' => 'Successful', 'message' => $response['message']]);
            } else {
                return redirect()->route('manager.campaign.list')->with('success', ['title' => 'Successful', 'message' => $response['message']]);
            }
        } else {
            return back()->withInput()->with('error', ['title' => 'Error while processing request', 'message' => $response['message']]);
        }
    }

    public function editPacingDetails($id): \Illuminate\Http\JsonResponse
    {
        $resultCampaign = $this->campaignRepository->find(base64_decode($id));
        if(!empty($resultCampaign)) {
            return response()->json(array('status' => true, 'message' => 'Data found', 'data' => $resultCampaign));
        } else {
            return response()->json(array('status' => false, 'message' => 'Data not found'));
        }
    }

    public function editSubAllocations($id): \Illuminate\Http\JsonResponse
    {
        $this->data['resultCampaign'] = $this->campaignRepository->find(base64_decode($id));
        $this->data['resultSubAllocations'] = $this->pacingDetailRepository->get(base64_decode($id));
        if(!empty($this->data['resultSubAllocations'])) {
            $start_date    = (new \DateTime($this->data['resultCampaign']->start_date));
            $end_date      = (new \DateTime($this->data['resultCampaign']->end_date));
        }
        $interval = \DateInterval::createFromDateString('1 month');
        $period   = new \DatePeriod($start_date, $interval, $end_date);
        $this->data['resultMonthList'] = array();
        $this->data['total_sub_allocation'] = 0;

        foreach ($period as $month) {
            $resultSubAllocations = $this->pacingDetailRepository->get(base64_decode($id), array('month' => $month->format("m"),'year' => $month->format("Y")));
            $this->data['resultMonthList'][] = array(
                    'month_name' => $month->format("M-Y"),
                    'month' => $month->format("m"),
                    'year' => $month->format("Y"),
                    'sub_allocations' => $resultSubAllocations,
                    'days' => $resultSubAllocations->pluck('day')->unique()->toArray()
                );
        }

        if(!empty($this->data)) {
            return response()->json(array('status' => true, 'message' => 'Data found', 'data' => $this->data));
        } else {
            return response()->json(array('status' => false, 'message' => 'Data not found'));
        }
    }

    public function update($id, Request $request)
    {
        $attributes = $request->all();
        $response = $this->campaignRepository->update(base64_decode($id), $attributes);
        if($request->ajax()) {
            if($response['status'] == TRUE) {
                return response()->json(array('status' => true, 'message' => $response['message']));
            } else {
                return response()->json(array('status' => false, 'message' => $response['message']));
            }
        } else {
            if($response['status'] == TRUE) {
                return redirect()->route('manager.campaign.show', $id)->with('success', ['title' => 'Successful', 'message' => $response['message']]);
            } else {
                return back()->withInput()->with('error', ['title' => 'Error while processing request', 'message' => $response['message']]);
            }

        }

    }

    public function updateSubAllocations($id, Request $request)
    {
        $attributes = $request->all();
        $response = $this->pacingDetailRepository->update(base64_decode($attributes['campaign_id']), $attributes);
        if($request->ajax()) {
            if($response['status'] == TRUE) {
                return response()->json(array('status' => true, 'message' => $response['message']));
            } else {
                return response()->json(array('status' => false, 'message' => $response['message']));
            }
        } else {
            if($response['status'] == TRUE) {
                return redirect()->route('manager.campaign.show', $id)->with('success', ['title' => 'Successful', 'message' => $response['message']]);
            } else {
                return back()->withInput()->with('error', ['title' => 'Error while processing request', 'message' => $response['message']]);
            }

        }

    }

    public function validateVMailCampaignId(Request $request)
    {
        $campaign = Campaign::query();
        $campaign = $campaign->where('v_mail_campaign_id',strtoupper($request->v_mail_campaign_id));

        if($request->has('campaign_id')) {
            $campaign = $campaign->where('id', '!=', base64_decode($request->campaign_id));
        }

        if($campaign->exists()) {
            return 'false';
        } else {
            return 'true';
        }
    }

    public function attachSpecification($id, Request $request): \Illuminate\Http\JsonResponse
    {
        $attributes = $request->all();
        $response = $this->campaignRepository->updateSpecification(base64_decode($id), $attributes);
        if($response['status'] == TRUE) {
            return response()->json(array('status' => true, 'message' => $response['message'], 'data' => $response['data']));
        } else {
            return response()->json(array('status' => false, 'message' => $response['message']));
        }
    }

    public function attachCampaignFile($id, Request $request): \Illuminate\Http\JsonResponse
    {
        $attributes = $request->all();
        $response = $this->campaignRepository->updateCampaignFile(base64_decode($id), $attributes);
        if($response['status'] == TRUE) {
            return response()->json(array('status' => true, 'message' => $response['message'], 'data' => $response['data']));
        } else {
            return response()->json(array('status' => false, 'message' => $response['message']));
        }
    }

    public function removeSpecification($id, Request $request): \Illuminate\Http\JsonResponse
    {
        $response = $this->campaignSpecificationRepository->destroy(base64_decode($id));
        if($response['status'] == TRUE) {
            return response()->json(array('status' => true, 'message' => $response['message']));
        } else {
            return response()->json(array('status' => false, 'message' => $response['message']));
        }
    }

    //Import bulk campaigns
    public function import(Request $request)
    {
        $attributes = $request->all();

        $response = $this->campaignRepository->import($attributes);

        if($response['status'] == TRUE) {
            return response(json_encode(array('status' => true, 'message' => $response['message'])), 201);
        } else {
            if(!empty($response['file'])) {
                return $response['file'];
                //return response(json_encode(array('status' => false, 'message' => $response['message'], 'file' => base64_encode($response['file']))));
            } else {
                return response(json_encode(array('status' => false, 'message' => $response['message'])));
            }
        }
    }

    public function getCampaigns(Request $request): \Illuminate\Http\JsonResponse
    {
        $filters = array_filter(json_decode($request->get('filters'), true));
        $search_data = $request->get('search');
        $searchValue = $search_data['value'];

        $draw = $request->get('draw');
        $limit = $request->get("length"); // Rows display per page
        $offset = $request->get("start");

        $query = Campaign::query();
        $totalRecords = $query->count();

        //Search Data
        if(isset($searchValue) && $searchValue != "") {
            $query->where("name", "like", "%$searchValue%");
        }
        //Filters
        if(!empty($filters)) {

        }

        //Order By
        $orderColumn = null;
        if ($request->has('order')){
            $order = $request->get('order');
            $orderColumn = $order[0]['column'];
            $orderDirection = $order[0]['dir'];
        }

        switch ($orderColumn) {
            case '0': $query->orderBy('campaign_id', $orderDirection); break;
            case '1': $query->orderBy('name', $orderDirection); break;
            case '2':
                break;
            case '3': $query->orderBy('start_date', $orderDirection); break;
            case '4': $query->orderBy('end_date', $orderDirection); break;
            case '5': $query->orderBy('allocation', $orderDirection); break;
            case '6': $query->orderBy('campaign_status_id', $orderDirection); break;
            default: $query->orderBy('created_at', 'DESC'); break;
        }

        $totalFilterRecords = $query->count();
        if($limit > 0) {
            $query->offset($offset);
            $query->limit($limit);
        }
        //Do not take incremental and reactivated
        $query->whereNull('parent_id');
        $query->with('children', function($children) {
            $children->orderBy('created_at', 'DESC');
        });


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
