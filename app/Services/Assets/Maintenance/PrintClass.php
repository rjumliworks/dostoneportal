<?php

namespace App\Services\Assets\Maintenance;

use App\Models\AssetEquipment;
use App\Models\AssetVehicle;
use App\Models\AssetBuilding;
use Illuminate\Database\Eloquent\Relations\Relation;

class PrintClass
{
    public function records($type, $id)
    {
        abort_unless(Relation::getMorphedModel($type), 404);

        $maintainable = match ($type) {
            'equipment' => AssetEquipment::with('station', 'detail')->findOrFail($id),
            'vehicle' => AssetVehicle::with('station', 'type', 'driver.profile')->findOrFail($id),
            'building' => AssetBuilding::with('station')->findOrFail($id),
        };

        $records = $maintainable->records()->with('performer.profile')->orderBy('date', 'ASC')->get();

        [$title, $infoRows, $filename] = $this->meta($type, $maintainable);

        $pdf = \PDF::loadView('prints.maintenance-record', [
            'title' => $title,
            'infoRows' => $infoRows,
            'records' => $records,
        ])->setPaper('a4', 'portrait');

        return $pdf->stream($filename);
    }

    protected function meta($type, $maintainable)
    {
        return match ($type) {
            'equipment' => [
                'EQUIPMENT MAINTENANCE RECORD',
                [
                    ['Equipment Name', $maintainable->name . ($maintainable->detail?->brand ? ' (' . $maintainable->detail->brand . ')' : '')],
                    ['Code No.', $maintainable->code],
                    ['Type/Model No.', $maintainable->detail?->model ?: '-'],
                    ['Serial No.', '-'],
                    ['Location', $maintainable->station?->name ?: '-'],
                ],
                'Maintenance-Record-' . $maintainable->code . '.pdf',
            ],
            'vehicle' => [
                'VEHICLE MAINTENANCE RECORD',
                [
                    ['Vehicle Name', $maintainable->name],
                    ['Code No.', $maintainable->code],
                    ['Plate No.', $maintainable->plate ?: '-'],
                    ['Type', $maintainable->type?->name ?: '-'],
                    ['Driver', $maintainable->driver?->profile?->fullname ?: '-'],
                    ['Location', $maintainable->station?->name ?: '-'],
                ],
                'Maintenance-Record-' . $maintainable->code . '.pdf',
            ],
            'building' => [
                'BUILDING MAINTENANCE RECORD',
                [
                    ['Building Name', $maintainable->name],
                    ['Code No.', $maintainable->code],
                    ['Address', $maintainable->address ?: '-'],
                    ['Location', $maintainable->station?->name ?: '-'],
                ],
                'Maintenance-Record-' . $maintainable->code . '.pdf',
            ],
        };
    }
}
