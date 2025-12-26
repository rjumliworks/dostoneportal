<?php

namespace App\Services;

use App\Models\UserRole;
use App\Models\ListRole;
use App\Models\ListUnit;
use App\Models\ListData;
use App\Models\ListStatus;
use App\Models\ListDropdown;
use App\Models\ListSalary;
use App\Models\ListDeduction;
use App\Models\ListPosition;
use App\Models\LocationRegion;
use App\Models\LocationProvince;
use App\Models\LocationMunicipality;
use App\Models\LocationDistrict;
use App\Models\LocationBarangay;

class DropdownClass
{  
    // public function __construct()
    // {
    //     $this->agency = (\Auth::user()->role != 'Administrator') ? (\Auth::user()->myroles) ? \Auth::user()->myroles[0]->agency_id : null : null;
    //     $this->ids = (\Auth::check()) ? (\Auth::user()->role == 'Administrator') ? [] : AgencyConfiguration::where('agency_id',$this->agency)->value('laboratories') : '';
    //     $this->laboratory = UserRole::where('user_id',\Auth::user()->id)->whereNotNull('laboratory_id')->pluck('laboratory_id');
    // }

    // public function suppliers(){
    //     $data = InventorySupplier::where('agency_id',$this->agency)->where('is_active',1)->get()->map(function ($item) {
    //         return [
    //             'value' => $item->id,
    //             'name' => $item->name
    //         ];
    //     });
    //     return $data;
    // }

    // public function services(){
    //     $data = TestserviceAddon::where('agency_id',$this->agency)->where('is_additional',0)->get()->map(function ($item) {
    //         return [
    //             'value' => $item->id,
    //             'label' => $item->name.' ('.$item->description.')',
    //             'name' => $item->name,
    //             'description' => $item->description,
    //             'fee' => $item->fee
    //         ];
    //     });
    //     return $data;
    // }

    public function dropdowns($class,$type = null){
        $data = ListDropdown::where('classification',$class)
        ->when($type, function ($query) use ($type){
            $query->where('type',$type);
        })
        ->get()->map(function ($item) {
            return [
                'value' => $item->id,
                'name' => $item->name,
                'others' => $item->others
            ];
        });
        return $data;
    }

    public function datas($type){
        $data = ListData::where('type',$type)->where('is_active',1)->get()->map(function ($item) {
            return [
                'value' => $item->id,
                'name' => $item->name
            ];
        });
        return $data;
    }

    public function units($code){
        $data = ListUnit::where('division_id',$code)->where('is_active',1)->get()->map(function ($item) {
            return [
                'value' => $item->id,
                'name' => $item->name,
                'short' => $item->short
            ];
        });
        return $data;
    }

    public function events(){
        $data = ListDropdown::where('classification','Events')->get()->map(function ($item) {
            return [
                'value' => $item->id,
                'name' => $item->name,
                'type' => $item->type,
                'color' => $item->color,
                'others' => $item->others
            ];
        });
        return $data;
    }

    public function stations(){
        $data = ListDropdown::where('classification','Station')->where('is_active',1)->get()->map(function ($item) {
            return [
                'value' => $item->id,
                'name' => $item->name.' ('.$item->others.')',
                'others' => $item->others
            ];
        });
        return $data;
    }

     public function salaries(){
        $data = ListSalary::orderBy('grade','ASC')->get()->map(function ($item) {
            return [
                'value' => $item->id,
                'name' => 'SG '.$item->grade.' ('.$item->amount.')',
                'grade' => $item->grade,
                'amount' => $item->amount, 
                'year' => $item->year,
                'is_regular' => $item->is_regular
            ];
        });
        return $data;
    }

    public function positions(){
        $data = ListPosition::with('salary')->get()->map(function ($item) {
            return [
                'value' => $item->id,
                'name' => $item->name,
                'salary_id' => $item->salary_id,
                'year' => $item->salary->year,
                'is_regular' => $item->is_regular
            ];
        });
        return $data;
    }

    public function deductions(){
        $data = ListDeduction::get()->map(function ($item) {
            return [
                'value' => $item->id,
                'name' => ($item->subtype != 'n/a') ? $item->name.' ('.$item->subtype.')' : $item->name,
                'subtype' => $item->subtype,
                'is_regular' => $item->is_regular,
                'is_contribution' => $item->is_contribution,
                'is_loan' => $item->is_loan,
                'is_enrollable' => $item->is_enrollable
            ];
        });
        return $data;
    }

    public function statuses($type){
        $data = ListStatus::where('classification',$type)->where('is_active',1)->get()->map(function ($item) {
            return [
                'value' => $item->id,
                'name' => $item->name,
                'type' => $item->type,
                'color' => $item->color,
                'others' => $item->others,
            ];
        });
        return $data;
    }

    public function roles(){
         $data = ListRole::where('is_active',1)
        ->whereIn('type', ['Staff', 'Hr'])
        ->get()
        ->groupBy(fn($item) => $item->type === 'Staff' ? 'Regular' : 'Human Resource')
        ->map(function ($items, $label) {
            return [
                'label' => $label,
                'options' => $items->map(fn($item) => [
                    'value' => $item->id,
                    'name' => $item->name
                ])->values()
            ];
        })
        ->values();
        return $data;
    }
    

    public function regions(){
        $data = LocationRegion::all()->map(function ($item) {
            return [
                'value' => $item->code,
                'name' => $item->region
            ];
        });
        return $data;
    }

    public function provinces($code){
        $data = LocationProvince::where('region_code',$code)->get()->map(function ($item) {
            return [
                'value' => $item->code,
                'name' => $item->name
            ];
        });
        return $data;
    }

    public function municipalities($code){
        $data = LocationMunicipality::where('province_code',$code)->get()->map(function ($item) {
            return [
                'value' => $item->code,
                'name' => $item->name
            ];
        });
        return $data;
    }

    public function barangays($code){
        $data = LocationBarangay::where('municipality_code',$code)->get()->map(function ($item) {
            return [
                'value' => $item->code,
                'name' => $item->name
            ];
        });
        return $data;
    }
}
