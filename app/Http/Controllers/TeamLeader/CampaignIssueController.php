<?php

namespace App\Http\Controllers\TeamLeader;

use App\Http\Controllers\Controller;
use App\Repository\Campaign\IssueRepository\IssueRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CampaignIssueController extends Controller
{
    private $data;
    /**
     * @var IssueRepository
     */
    private $issueRepository;

    public function __construct(
        IssueRepository $issueRepository
    )
    {
        $this->data = array();
        $this->issueRepository = $issueRepository;
    }

    public function update($id, Request $request)
    {
        $attributes = $request->all();
        $attributes['closed_by'] = Auth::id();
        $attributes['status'] = 1;
        $response = $this->issueRepository->update(base64_decode($id), $attributes);
        if($response['status'] == TRUE) {
            return response()->json(array('status' => true, 'message' => 'Issue closed successfully'));
        } else {
            return response()->json(array('status' => false, 'message' => $response['message']));
        }
    }
}
