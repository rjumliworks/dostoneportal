<?php

namespace App\Http\Controllers\Others;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Program;
use App\Models\Project;
use App\Models\Budget;
use App\Models\BudgetItem;
use App\Models\BudgetAllocation;

class ProjectController extends Controller
{
    public function index(Request $request){
        switch($request->option){
            default:
                return inertia('Modules/Others/Projects/Index',[
                    'info' => [
                        'total' => $this->total(),
                        'collection' => $this->collection(),
                        'statuses' => $this->statuses(),
                        'programs' => $this->programs()
                    ]
                ]); 
        }   
    }

    private function programs(){
            $data = Program::with([
            'projects' => function ($query) {
                $query->withSum('allocations', 'amount');
            }
        ])->get();
        return $data;
    }

    private function total(){
        $data = BudgetItem::whereHas('budget', function ($q) {
            $q->where('year', 2026);
        })->sum('amount');
        return $data;
    }

    public function collection()
{
    return Budget::with('type', 'items.allocations')
        ->where('year', date('Y'))
        ->get()
        ->groupBy(fn ($budget) => $budget->type->name ?? 'Unknown')
        ->map(function ($budgets, $typeName) {

            $total = $budgets->flatMap(function ($b) {
                return $b->items ?? collect();
            })->sum('amount');

            $allocated = $budgets->flatMap(function ($b) {
                return $b->items;
            })->flatMap(function ($item) {
                return $item->allocations ?? collect();
            })->sum('amount');

            $percent = $total > 0
                ? ($allocated / $total) * 100
                : 0;

            return [
                'name' => $typeName,
                'description' => 'Successfully collected and receipted',
                'total' => $total,
                'allocated' => $allocated,
                'percent_allocated' => round($percent, 2),
                'icon' => 'ri-checkbox-circle-fill fs-20',
                'color' => 'text-success'
            ];
        })
        ->values();
}

    public function statuses()
{
    return [
        [
            'name' => 'Allocated',
            'description' => 'Budget has been distributed to projects or units and is available for use.',
            'total' => BudgetAllocation::sum('amount'),
            'icon' => 'ri-funds-box-fill fs-20',
            'color' => 'text-success'
        ],
        [
            'name' => 'Obligated',
            'description' => 'Funds have been legally committed through approved obligations (ORS/BURS) but not yet paid.',
            'total' => 0,
            'icon' => 'ri-file-list-3-fill fs-20',
            'color' => 'text-warning'
        ],
        [
            'name' => 'Disbursed',
            'description' => 'Actual cash payments released and recorded through disbursement vouchers.',
            'total' => 0,
            'icon' => 'ri-bank-card-fill fs-20',
            'color' => 'text-primary'
        ]
    ];
}
}
