<?php

namespace App\Repository\Campaign\IssueRepository;

use App\Models\CampaignIssue;

class IssueRepository implements IssueInterface
{
    /**
     * @var CampaignIssue
     */
    private $campaignIssue;

    public function __construct(
        CampaignIssue $campaignIssue
    )
    {
        $this->campaignIssue = $campaignIssue;
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
        // TODO: Implement store() method.
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
