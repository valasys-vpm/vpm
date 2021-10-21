<?php

namespace App\Repository\Suppression\Domain;

use App\Models\SuppressionDomain;
use Illuminate\Support\Facades\DB;
use Excel;

class DomainRepository implements DomainInterface
{
    private $suppressionDomain;

    public function __construct(
        SuppressionDomain $suppressionDomain
    )
    {
        $this->suppressionDomain = $suppressionDomain;
    }

    public function get($filters = array())
    {
        // TODO: Implement get() method.
    }

    public function find($id)
    {
        // TODO: Implement find() method.
    }

    public function store($attributes)
    {
        $response = array('status' => FALSE, 'message' => 'Something went wrong, please try again.');
        try {
            DB::beginTransaction();

            $suppressionDomain = new SuppressionDomain();
            $suppressionDomain->campaign_id = $attributes['campaign_id'];
            $suppressionDomain->domain = $attributes['domain'];
            $suppressionDomain->save();

            if($suppressionDomain->id) {
                DB::commit();
                $response = array('status' => TRUE, 'message' => 'Domain suppression added successfully');
            } else {
                throw new \Exception('Something went wrong, please try again.', 1);
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            //dd($exception->getMessage());
            $response = array('status' => FALSE, 'message' => 'Something went wrong, please try again.');
        }
        return $response;
    }

    public function bulkUpload($campaign_id, $file)
    {

        $response = array('status' => FALSE, 'message' => 'Something went wrong, please try again.');
        try {
            DB::beginTransaction();

            $excelData = Excel::toArray('', $file);
            array_shift($excelData[0]);

            $suppressionDomains = array();
            foreach ($excelData[0] as $key => $row) {
                $suppressionDomains[] = array(
                    'campaign_id' => $campaign_id,
                    'domain' => trim($row[0]),
                );
            }

            if(!empty($suppressionDomains) && count($suppressionDomains)) {
                SuppressionDomain::insert($suppressionDomains);
                DB::commit();
                $response = array('status' => TRUE, 'message' => 'Suppression domains added successfully');
            } else {
                throw new \Exception('Something went wrong, please try again.', 1);
            }
        } catch (\Exception $exception) {
            DB::rollBack();
            //dd($exception->getMessage());
            $response = array('status' => FALSE, 'message' => 'Something went wrong, please try again.');
        }
        return $response;
    }

    public function update($id, $attributes)
    {
        // TODO: Implement update() method.
    }

    public function destroy($id)
    {
        // TODO: Implement destroy() method.
    }
}
