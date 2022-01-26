<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\AgentLead;
use App\Models\AgentWorkType;
use App\Models\CampaignAssignAgent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    private $data;

    public function __construct()
    {
        $this->data = array();
    }

    public function index()
    {
        $user_id = Auth::id();

        $queryCAAgent = CampaignAssignAgent::query()->where('user_id', $user_id);
        $queryAgentLead = AgentLead::query()->where('agent_id', $user_id);

        $this->data['total_campaigns'] = $total_campaigns = $queryCAAgent->count();
        $this->data['leads_allocated'] = $leads_allocated = $queryCAAgent->sum('allocation');

        //Campaign Processed
        $this->data['campaign_processed']['count'] = $queryCAAgent->whereNotNull('submitted_at')->count();
        $campaign_processed_percentage = $total_campaigns > 0 ? ($this->data['campaign_processed']['count'] / $total_campaigns) * 100 : 0;
        $this->data['campaign_processed']['percentage'] = number_format((float)$campaign_processed_percentage, 2, '.', '');

        //Leads Generated
        $this->data['leads_generated']['count'] = $queryAgentLead->count();
        $leads_generated_percentage = $leads_allocated > 0 ? ($this->data['leads_generated']['count'] / $leads_allocated) * 100 : 0;
        $this->data['leads_generated']['percentage'] = number_format((float)$leads_generated_percentage, 2, '.', '');

        //Leads Qualified
        $this->data['leads_qualified']['count'] = $queryAgentLead->where('status', 1)->count();
        $leads_qualified_percentage = $this->data['leads_generated']['count'] > 0 ? ($this->data['leads_qualified']['count'] / $this->data['leads_generated']['count']) * 100 : 0;
        $this->data['leads_qualified']['percentage'] = number_format((float)$leads_qualified_percentage, 2, '.', '');

        //Leads Rejected
        $this->data['leads_rejected']['count'] = $queryAgentLead->where('status', 0)->count();
        $leads_rejected_percentage = $this->data['leads_generated']['count'] > 0 ? ($this->data['leads_rejected']['count'] / $this->data['leads_generated']['count']) * 100 : 0;
        $this->data['leads_rejected']['percentage'] = number_format((float)$leads_rejected_percentage, 2, '.', '');
        //dd($this->data);

        return view('agent.dashboard', $this->data);
        //number_format((float)$foo, 2, '.', '');
    }

    public function getData(Request $request)
    {
        $user_id = Auth::id();
        $response = array();
        $attributes = $request->all();

        $queryCAAgent = CampaignAssignAgent::query()->where('user_id', $user_id);
        $queryAgentLead = AgentLead::query()->where('agent_id', $user_id);

        $response['total_campaigns'] = $total_campaigns = $queryCAAgent->count();
        $response['leads_allocated'] = $leads_allocated = $queryCAAgent->sum('allocation');

        //Campaign Processed
        $response['campaign_processed']['count'] = $queryCAAgent->whereNotNull('submitted_at')->count();
        $campaign_processed_percentage = $total_campaigns > 0 ? ($response['campaign_processed']['count'] / $total_campaigns) * 100 : 0;
        $response['campaign_processed']['percentage'] = number_format((float)$campaign_processed_percentage, 2, '.', '');

        //Leads Generated
        $response['leads_generated']['count'] = $queryAgentLead->count();
        $leads_generated_percentage = $leads_allocated > 0 ? ($response['leads_generated']['count'] / $leads_allocated) * 100 : 0;
        $response['leads_generated']['percentage'] = number_format((float)$leads_generated_percentage, 2, '.', '');

        //Leads Qualified
        $response['leads_qualified']['count'] = $queryAgentLead->where('status', 1)->count();
        $leads_qualified_percentage = $response['leads_generated']['count'] > 0 ? ($response['leads_qualified']['count'] / $response['leads_generated']['count']) * 100 : 0;
        $response['leads_qualified']['percentage'] = number_format((float)$leads_qualified_percentage, 2, '.', '');

        //Leads Rejected
        $response['leads_rejected']['count'] = $queryAgentLead->where('status', 0)->count();
        $leads_rejected_percentage = $response['leads_generated']['count'] > 0 ? ($response['leads_rejected']['count'] / $response['leads_generated']['count']) * 100 : 0;
        $response['leads_rejected']['percentage'] = number_format((float)$leads_rejected_percentage, 2, '.', '');

        return response()->json($response);
    }

    public function getGuageChartData(Request $request)
    {
        $user_id = Auth::id();
        $response = array();
        $attributes = $request->all();

        $response['guage_chart']['productivity'] = 52;
        $response['guage_chart']['quality'] = 98;

        return response()->json($response);
    }

    public function getCountsByWorkTypeBarChartData(Request $request)
    {
        $user_id = Auth::id();
        $response = array();
        $attributes = $request->all();

        $resultAgentWorkTypes = AgentWorkType::where('status', 1)->get();

        $response['bar_chart']['xAxis'] = $resultAgentWorkTypes->pluck('name')->toArray();

        $data = array();
        $colors = array('#ff598f','#fd8a5e','#e0e300','#01dddd','#00bfaf');

        if(!empty($resultAgentWorkTypes) && $resultAgentWorkTypes->count()) {
            foreach($resultAgentWorkTypes as $key => $agentWorkType) {
                $data[] = array(
                    'value' => 50,
                    'itemStyle' => array('color' => $colors[$key])
                );
            }
        }

        $response['bar_chart']['data'] = $data;

        return response()->json($response);
    }

    public function getLeadsGeneratedCountsBarChartData(Request $request)
    {
        $user_id = Auth::id();
        $response = array();
        $attributes = $request->all();

        $start_date = date('Y-m-01', strtotime($attributes['month']));
        $end_date = date('Y-m-t', strtotime($attributes['month']));

        $queryAgentLead = AgentLead::query();
        $queryAgentLead->whereBetween('created_at', [$start_date, $end_date]);
        $resultAgentLeads = $queryAgentLead->get();

        $begin = new \DateTime($start_date);
        $end = new \DateTime($end_date);
        $end->modify('first day of next month');
        $interval = \DateInterval::createFromDateString('1 day');
        $period = new \DatePeriod($begin, $interval, $end);

        foreach ($period as $dt) {
            //echo $dt->format("l Y-m-d H:i:s\n");
            $response['bar_chart']['xAxis'][] = (integer) $dt->format('d');

            $count = 0;
            foreach($resultAgentLeads as $key => $agentLead){
                if(date('Y-m-d', strtotime($agentLead->created_at)) == $dt->format("Y-m-d")) {
                    $count++;
                }
            }
            $response['bar_chart']['data'][] = (integer) $count;
        }

        return response()->json($response);
    }
}
