<?php

namespace App\Services\Assets\Equipment;

use App\Models\AssetEquipment;
use App\Models\AssetMaintenanceRecord;

class PrintClass
{
    public function schedule($year = null)
    {
        $year = $year ?: now()->year;

        $equipment = AssetEquipment::with('type')->orderBy('code', 'ASC')->get(['id', 'code', 'name', 'type_id', 'maintenance_schedule']);

        $completedByEquipment = AssetMaintenanceRecord::where('maintainable_type', 'equipment')
            ->whereYear('date', $year)
            ->whereHas('status', function ($q) {
                $q->where('classification', 'Maintenance Record')->where('name', 'Completed');
            })
            ->get(['maintainable_id', 'date'])
            ->groupBy('maintainable_id');

        $rows = $equipment->map(function ($item) use ($completedByEquipment) {
            $completed = ($completedByEquipment->get($item->id) ?? collect())
                ->map(fn ($record) => (int) date('n', strtotime($record->date)))
                ->unique()
                ->values()
                ->all();

            return [
                'code' => $item->code,
                'name' => $item->name,
                'type' => $item->type?->name,
                'planned' => $item->maintenance_schedule ?: [],
                'completed' => $completed,
            ];
        });

        $pdf = \PDF::loadView('prints.equipment-maintenance-schedule', [
            'year' => $year,
            'rows' => $rows,
        ])->setPaper('a4', 'landscape');

        $pdf->render();
        $pdf->getDomPDF()->getCanvas()->page_text(740, 558, 'Page {PAGE_NUM} of {PAGE_COUNT}', null, 9, [0.3, 0.3, 0.3]);

        return $pdf->stream('Equipment-Maintenance-Schedule-' . $year . '.pdf');
    }
}
