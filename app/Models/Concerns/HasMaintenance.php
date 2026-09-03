<?php

namespace App\Models\Concerns;

use App\Models\AssetMaintenanceRecord;
use App\Models\AssetMaintenanceRequest;

trait HasMaintenance
{
    public function records()
    {
        return $this->morphMany(AssetMaintenanceRecord::class, 'maintainable')->orderBy('date', 'DESC');
    }

    public function maintenanceRequests()
    {
        return $this->morphMany(AssetMaintenanceRequest::class, 'maintainable')->orderBy('requested_at', 'DESC');
    }
}
