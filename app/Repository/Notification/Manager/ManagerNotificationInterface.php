<?php

namespace App\Repository\Notification\Manager;

interface ManagerNotificationInterface
{
    public function get($filters = array());
    public function find($id);
    public function store($attributes);
    public function destroy($id);
}
