<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class CronTriggerController extends Controller
{
    private $data;

    public function __construct()
    {
        $this->data = array();
    }

    public function index()
    {
        return view('admin.cron_trigger.list');
    }

    public function DailyReportLog()
    {
        Artisan::call('dailyreportlog:cron');
        $response = Artisan::output();
        if(trim($response) == 'true') {
            return back()->with('success', ['title' => 'Successful', 'message' => 'Daily Report Logs: Cron completed successfully.']);
        } else {
            return back()->with('error', ['title' => 'Error while processing request', 'message' => trim($response)]);
        }
    }
}
