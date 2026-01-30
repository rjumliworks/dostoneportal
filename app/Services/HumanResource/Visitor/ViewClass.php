<?php

namespace App\Services\HumanResource\Visitor;

use Carbon\Carbon;
use Hashids\Hashids;
use App\Models\Visitor;
use App\Models\VisitorLogs;
use App\Models\ListName;
use App\Http\Resources\Portal\Dtr\TimeResource;
use App\Http\Resources\HumanResource\Visitor\IndexResource;

class ViewClass
{
    public function counts($types){
        foreach($types as $type){
            $counts[] = Visitor::where('status_id', $type)->count();
        }
        return $counts;
    }

    public function visitor($code){
        $hashids = new Hashids('krad',10);
        $id = $hashids->decode($code);

        $data = new IndexResource(
           Visitor::query()
            ->with('type','status','affiliation','designation','logs','faces')
            ->where('id',$id)->first()
        );
        return $data;
    }

    public function lists($request){
        $data = IndexResource::collection(
            Visitor::with('type','status','affiliation','designation')
            ->when($request->keyword, function ($query,$keyword) {
                $query->where('name', 'LIKE', "%{$keyword}%");
            })
            ->when($request->type, function ($query,$type) {
                $query->where('type_id',$type);
            })
            ->when($request->status, function ($query,$status) {
                $query->where('status_id',$status);
            })
            ->orderBy('created_at', 'DESC')
            ->paginate($request->count)
        );
        return $data;
    }

    public function search($request){
        $keyword = $request->keyword;
        $type = $request->type;

        $data = ListName::where('name', 'LIKE', "%{$keyword}%")
        ->where('type',$type)
        ->where('is_active',1)
        ->limit(20)
        ->get()->map(function ($item) {
            return [
                'value' => $item->id,
                'name' => $item->name,
            ];
        });
        return $data;
    }

    public function logs($request){
        $hashids = new Hashids('krad',10);
        $id = $hashids->decode($request->code);

        
        $monthName = $request->month;
        $year  = ($request->year) ? $request->year : date('Y');
        $month = Carbon::parse($monthName)->month;

        $start = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $end   = Carbon::createFromDate($year, $month, 1)->endOfMonth();
        
        $data =  VisitorLogs::where('visitor_id',$id[0])->get()->map(function ($dtr) {
            return [
                'id' => $dtr->id,
                'date' => $dtr->date,
                'am_in'  => $dtr && $dtr->am_in_at  ? new TimeResource(json_decode($dtr->am_in_at)) : null,
                'am_out' => $dtr && $dtr->am_out_at ? new TimeResource(json_decode($dtr->am_out_at)) : null,
                'pm_in'  => $dtr && $dtr->pm_in_at  ? new TimeResource(json_decode($dtr->pm_in_at))  : null,
                'pm_out' => $dtr && $dtr->pm_out_at ? new TimeResource(json_decode($dtr->pm_out_at)) : null,
                'created_at' => $dtr->created_at
            ];
        });

        return $data;
    }

    private function formatDTR($item, $start, $end)
    {
        $visitor_id = $item->id;
        $period = \Carbon\CarbonPeriod::create($start, $end);

        foreach ($period as $date) {
            $dateStr = $date->toDateString();
           
            $dtr = $item->logs->firstWhere('date', $dateStr);
            $today = Carbon::today();
        
            $result[] = [
                'date' => $dateStr,
                'am_in'  => $dtr && $dtr->am_in_at  ? new TimeResource(json_decode($dtr->am_in_at)) : null,
                'am_out' => $dtr && $dtr->am_out_at ? new TimeResource(json_decode($dtr->am_out_at)) : null,
                'pm_in'  => $dtr && $dtr->pm_in_at  ? new TimeResource(json_decode($dtr->pm_in_at))  : null,
                'pm_out' => $dtr && $dtr->pm_out_at ? new TimeResource(json_decode($dtr->pm_out_at)) : null,
                'created_at' => $dtr->created_at
            ];
        }

        return [
            'value' => $item->id,
            'name' => $item->name . '.',
            'avatar' => ($item->avatar !== 'noavatar.jpg')
                ? asset('storage/' . $item->avatar)
                : asset('images/avatars/avatar.jpg'),
            'dtrs' => $result
        ];
    }
}
