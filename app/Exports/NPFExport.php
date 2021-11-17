<?php

namespace App\Exports;

use App\Models\AgentLead;
use App\Models\Campaign;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class NPFExport implements FromCollection, WithHeadings, WithEvents, WithColumnFormatting
{
    private $campaignId;

    public function __construct($campaignId = null)
    {
        $this->campaignId = $campaignId;
    }

    public function collection()
    {
        $exportData = [];

        //get data
        $resultCampaign = Campaign::find($this->campaignId);

        $query = AgentLead::query();
        $query->whereCampaignId($this->campaignId);
        $resultAgentLeads = $query->get();

        //convert to final array
        foreach($resultAgentLeads as $key => $agentLead) {

            $exportData[$key]['Date'] = date('d-M-Y');
            $exportData[$key]['First Name'] = $agentLead->first_name;
            $exportData[$key]['Last Name'] = $agentLead->last_name;
            $exportData[$key]['Company Name'] = $agentLead->company_name;
            $exportData[$key]['Job Title'] = $agentLead->specific_title;
            $exportData[$key]['Job Level'] = $agentLead->job_level;
            $exportData[$key]['Email Address'] = $agentLead->email_address;
            $exportData[$key]['Phone Number'] = $agentLead->phone_number;
            $exportData[$key]['Address 1'] = $agentLead->address_1;
            $exportData[$key]['City'] = $agentLead->city;
            $exportData[$key]['State'] = $agentLead->state;
            $exportData[$key]['Zipcode'] = $agentLead->zipcode;
            $exportData[$key]['Country'] = $agentLead->country;
            $exportData[$key]['Industry'] = $agentLead->industry;
            $exportData[$key]['Employee Size'] = $agentLead->employee_size;
            $exportData[$key]['Company Revenue'] = $agentLead->revenue;
            $exportData[$key]['Asset'] = '';
        }
        //dd($exportData);
        return collect($exportData);
    }

    public function headings(): array
    {
        return [

            'Date',
            'First Name',
            'Last Name',
            'Company Name',
            'Job Title',
            'Job Level',
            'Email Address',
            'Phone Number',
            'Address 1',
            'City',
            'State',
            'Zipcode',
            'Country',
            'Industry',
            'Employee Size',
            'Revenue',
            'Asset',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getStyle('1')->applyFromArray([
                    'font' => [
                        'bold' => true
                    ]
                ]);

                $event->sheet->getStyle('H')->getNumberFormat()->setFormatCode('#0');

                /*$event->sheet->getStyle('M1')->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'color' => ['rgb' => '#FFFAA9']
                    ]
                ]);*/
            },
        ];
    }

    public function columnFormats(): array
    {
        return [
            //'K' => NumberFormat::FORMAT_GENERAL,
        ];
    }

}
