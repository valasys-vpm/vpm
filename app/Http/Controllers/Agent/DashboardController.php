<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\AgentLead;
use App\Models\AgentWorkType;
use App\Models\CampaignAssignAgent;
use App\Models\DailyReportLog;
use App\Models\User;
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

        return view('agent.dashboard', $this->data);
        //number_format((float)$foo, 2, '.', '');
    }

    public function getData(Request $request)
    {
        $user_id = Auth::id();
        $response = array();
        $attributes = $request->all();
        $start_date = date('Y-m-d', strtotime($attributes['start_date']));
        $end_date = date('Y-m-d', strtotime($attributes['end_date']));

        $queryCAAgent = CampaignAssignAgent::query()->where('user_id', $user_id);
        $queryAgentLead = AgentLead::query()->where('agent_id', $user_id);
        $queryAgentLead->whereBetween('created_at', [$start_date, $end_date]);

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
        $response = array();
        $attributes = $request->all();
        $start_date = date('Y-m-d', strtotime($attributes['start_date']));
        $end_date = date('Y-m-d', strtotime($attributes['end_date']));

        $query = DailyReportLog::query();
        $query->where('user_id', Auth::id());
        $query->whereBetween('created_at', [$start_date, $end_date]);
        $resultDailyReportLogs = $query->get();

        $total_productivity = $resultDailyReportLogs->sum('productivity');
        $total_quality = $resultDailyReportLogs->sum('quality');
        $total_days = $resultDailyReportLogs->count();

        $productivity = $total_days > 0 ? $total_productivity / $total_days : 0;
        $quality = $total_days > 0 ? $total_quality / $total_days : 0;

        $response['guage_chart']['productivity'] = (int) number_format((float)($productivity), 0, '.', '');
        $response['guage_chart']['quality'] = (int) number_format((float)($quality), 0, '.', '');

        return response()->json($response);
    }

    public function getTopProductivityData(Request $request)
    {
        $response = array();
        $attributes = $request->all();
        $start_date = date('Y-m-d', strtotime($attributes['start_date']));
        $end_date = date('Y-m-d', strtotime($attributes['end_date']));

        $query_user = User::query();
        $query_user->whereHas('role', function ($query_role) {
            $query_role->whereIn('slug', array('research_analyst', 'email_marketing_executive', 'vendor_management'));
        });
        $resultAgents = $query_user->where('status', 1)->get();

        $resultProductivity = array();
        $resultQuality = array();
        $resultData = array();

        foreach ($resultAgents as $user) {
            $query = DailyReportLog::query();
            $query->where('user_id', $user->id);
            $query->whereBetween('created_at', [$start_date, $end_date]);
            $resultDailyReportLogs = $query->get();

            if($resultDailyReportLogs->count()) {
                $total_days = $resultDailyReportLogs->count();
                $total_productivity = $resultDailyReportLogs->sum('productivity');
                $total_quality = $resultDailyReportLogs->sum('quality');

                $productivity = $total_productivity / $total_days;
                $quality = $total_quality / $total_days;
            } else{
                $productivity = 0;
                $quality = 0;
            }

            $resultData[$user->id]['user'] = $user->toArray();
            $resultData[$user->id]['productivity'] = (int) number_format((float)($productivity), 0, '.', '');
            $resultData[$user->id]['quality'] = (int) number_format((float)($quality), 0, '.', '');

            $resultData[$user->id]['round_productivity'] = $resultData[$user->id]['productivity'] > 0 ? round_up_to_any_nearest($resultData[$user->id]['productivity'], 5): 0;
            $resultData[$user->id]['round_quality'] = $resultData[$user->id]['quality'] > 0 ? round_up_to_any_nearest($resultData[$user->id]['quality'], 5) : 0;

            $resultProductivity[$user->id] = (int) number_format((float)($productivity), 0, '.', '');
            $resultQuality[$user->id] = (int) number_format((float)($quality), 0, '.', '');
        }

        $arrayProductivity = $arrayQuality = $resultData;
        array_multisort($resultProductivity, SORT_DESC, $arrayProductivity);
        $response['top_productivity'] = array_slice($arrayProductivity, 0, 3, true);

        array_multisort($resultQuality, SORT_DESC, $arrayQuality);
        $response['top_quality'] = array_slice($arrayQuality, 0, 3, true);

        return response()->json($response);
    }

    public function getCountsByWorkTypeBarChartData(Request $request)
    {
        $response = array();
        $attributes = $request->all();
        $start_date = date('Y-m-d', strtotime($attributes['start_date']));
        $end_date = date('Y-m-01 11:59:59', strtotime('+1 month', strtotime($start_date)));

        $response['bar_chart']['xAxis'] = array();
        $response['bar_chart']['data'] = array();
        $colors = array('#ff598f','#fd8a5e','#e0e300','#01dddd','#00bfaf');

        $resultAgentWorkTypes = AgentWorkType::where('status', 1)->get();

        if(!empty($resultAgentWorkTypes) && $resultAgentWorkTypes->count()) {
            foreach($resultAgentWorkTypes as $key => $agentWorkType) {

                $query = AgentLead::query();
                $query->where('agent_id', Auth::id());
                $query->whereBetween('created_at', [$start_date, $end_date]);
                $query->whereHas('ca_agent', function($ca_agent) use($agentWorkType) {
                    $ca_agent->where('agent_work_type_id', $agentWorkType->id);
                });

                $response['bar_chart']['data'][] = array(
                    'value' => $query->count(),
                    'itemStyle' => array('color' => $colors[$key])
                );
                $response['bar_chart']['xAxis'][] = $agentWorkType->name;
            }
        }
        return response()->json($response);
    }

    public function getLeadsGeneratedCountsBarChartData(Request $request)
    {
        $user_id = Auth::id();
        $response = array();
        $attributes = $request->all();

        $start_date = date('Y-m-01 12:00:00', strtotime($attributes['month']));
        $end_date = date('Y-m-01 11:59:59', strtotime('+1 month', strtotime($start_date)));

        $queryAgentLead = AgentLead::query();
        $queryAgentLead->where('agent_id', $user_id);
        $queryAgentLead->whereBetween('created_at', [$start_date, $end_date]);
        $resultAgentLeads = $queryAgentLead->get();

        $begin = new \DateTime($start_date);
        $end = new \DateTime($end_date);
        $interval = \DateInterval::createFromDateString('1 day');
        $period = new \DatePeriod($begin, $interval, $end);

        foreach ($period as $dt) {
            $response['bar_chart']['xAxis'][] = (integer) $dt->format('d');
            $count = 0;
            foreach($resultAgentLeads as $key => $agentLead){
                if(date('Y-m-d', strtotime($agentLead->created_at)) == $dt->format("Y-m-d")) {
                    $count++;
                }
            }
            $response['bar_chart']['data'][] = (integer) $count;
        }
        $response['bar_chart']['legend_data'] = array('Month', $attributes['month']);
        return response()->json($response);
    }
}
