<?php

namespace App\Http\Controllers\VendorManager;

use App\Http\Controllers\Controller;
use App\Models\Campaign;
use App\Repository\CampaignAssignRepository\CampaignAssignRepository;
use App\Repository\UserRepository\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CampaignAssignController extends Controller
{
    private $data;
    private $campaignAssignRepository;
    private $userRepository;
    public function __construct(
        CampaignAssignRepository $campaignAssignRepository,
        UserRepository $userRepository
    )
    {
        $this->data = array();
        $this->campaignAssignRepository = $campaignAssignRepository;
        $this->userRepository = $userRepository;
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $attributes = $request->all();
        dd($attributes);
        $response = $this->campaignAssignRepository->store($attributes);
        if($response['status'] == TRUE) {
            return redirect()->route('manager.campaign_assign.list')->with('success', ['title' => 'Successful', 'message' => $response['message']]);
        } else {
            return back()->withInput()->with('error', ['title' => 'Error while processing request', 'message' => $response['message']]);
        }
    }
}
