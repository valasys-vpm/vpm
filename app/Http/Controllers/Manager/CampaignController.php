<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Repository\CampaignFilterRepository\CampaignFilterRepository;
use App\Repository\CampaignRepository\CampaignRepository;
use App\Repository\CampaignStatusRepository\CampaignStatusRepository;
use App\Repository\CampaignTypeRepository\CampaignTypeRepository;
use App\Repository\CountryRepository\CountryRepository;
use App\Repository\HolidayRepository\HolidayRepository;
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

    public function __construct(
        CampaignStatusRepository $campaignStatusRepository,
        CampaignFilterRepository $campaignFilterRepository,
        CampaignTypeRepository $campaignTypeRepository,
        CountryRepository $countryRepository,
        RegionRepository $regionRepository,
        HolidayRepository $holidayRepository,
        CampaignRepository $campaignRepository
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
    }

    public function index()
    {
        return view('manager.campaign.list', $this->data);
    }

    public function show($id)
    {
        try {
            $this->data['resultCampaignStatuses'] = $this->campaignStatusRepository->get(array('status' => 1));
            $this->data['resultCampaign'] = $this->campaignRepository->find(base64_decode($id), array('pacingDetails'));
            //dd($this->data['resultCampaign']->toArray());
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

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $attributes = $request->all();
        $response = $this->campaignRepository->store($attributes);
        if($response['status'] == TRUE) {
            return redirect()->route('manager.campaign.list')->with('success', ['title' => 'Successful', 'message' => $response['message']]);
        } else {
            return back()->withInput()->with('error', ['title' => 'Error while processing request', 'message' => $response['message']]);
        }
    }

    public function edit($id)
    {
        try {
            $this->data['resultCampaign'] = $this->campaignRepository->find(base64_decode($id));
            dd($this->data['resultCampaign']->toArray());
            return view('manager.campaign.edit', $this->data);
        } catch (\Exception $exception) {
            return redirect()->route('manager.campaign.list')->with('error', ['title' => 'Error while processing request', 'message' => 'Campaign details not found']);
        }
    }

    public function update($id, Request $request): \Illuminate\Http\RedirectResponse
    {
        $attributes = $request->all();
        $response = $this->campaignRepository->update(base64_decode($id), $attributes);
        if($response['status'] == TRUE) {
            return redirect()->route('manager.campaign.list')->with('success', ['title' => 'Successful', 'message' => $response['message']]);
        } else {
            return back()->withInput()->with('error', ['title' => 'Error while processing request', 'message' => $response['message']]);
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

    public function getCampaigns(Request $request): \Illuminate\Http\JsonResponse
    {
        $filters = array_filter(json_decode($request->get('filters'), true));
        $search_data = $request->get('search');
        $searchValue = $search_data['value'];
        $order = $request->get('order');
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
        if(!empty($filters)) { }


        //Order By
        $orderColumn = $order[0]['column'];
        $orderDirection = $order[0]['dir'];
        switch ($orderColumn) {
            case '0': $query->orderBy('name', $orderDirection); break;
            case '1': $query->orderBy('name', $orderDirection); break;
            case '2': $query->orderBy('name', $orderDirection); break;
            case '3': $query->orderBy('name', $orderDirection); break;
            case '4': $query->orderBy('name', $orderDirection); break;
            default: $query->orderBy('name'); break;
        }

        $totalFilterRecords = $query->count();
        if($limit > 0) {
            $query->offset($offset);
            $query->limit($limit);
        }
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
