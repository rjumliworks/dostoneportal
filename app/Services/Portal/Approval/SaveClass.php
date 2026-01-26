<?php

namespace App\Services\Portal\Approval;

use Hashids\Hashids;
use App\Models\Request;
use App\Models\RequestReport;
use App\Models\RequestOvertime;
use App\Models\RequestTravelCode;
use App\Models\CreditLog;
use App\Models\UserCredit;
use App\Models\RequestLeave;
use App\Models\OrgSignatory;
use App\Models\OrgSignatorySchedule;
use App\Models\RequestSignatory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SaveClass
{
    public function status($request)
    {
        try {
            $hashids = new Hashids('krad', 10);
            $signatoryId = $hashids->decode($request->id)[0] ?? null;
            $requestId   = $hashids->decode($request->request_id)[0] ?? null;

            if (!$requestId) {
                return [
                    'data' => null,
                    'message' => 'Invalid request ID',
                    'info' => 'The request ID provided could not be verified. Please try again or contact your administrator.',
                ];
            }

            $data = Request::find($requestId);

            if (!$data) {
                return [
                    'data' => null,
                    'message' => 'Request not found',
                    'info' => 'The request record could not be found. It may have been deleted or is no longer accessible.',
                ];
            }

            $user = OrgSignatorySchedule::where('user_id', auth()->id())
                ->where('is_ongoing', 1)
                ->first();

            $division = OrgSignatory::where(function ($q) {
                    $q->where('user_id', auth()->id())
                    ->orWhere('oic_id', auth()->id());
                })
                ->where('is_active', 1)
                ->first()?->designationable?->assigned;

            if (!$division) {
                return [
                    'data' => $data,
                    'message' => 'No active division found',
                    'info' => 'The system could not find your active division assignment. Please check your signatory status or contact HR/Admin.',
                ];
            }

            $query = RequestSignatory::where('id', $signatoryId)->where('request_id', $data->id);
            ($request->status_id != 26) ? $query->where('division_id', $division->id) : '';
            $signatory = $query->first();

            if (!$signatory) {
                $query = RequestSignatory::where('id', $signatoryId)->where('request_id', $data->id);
                $query->where('division_id', 48);
                $signatory = $query->first();

                if(!$signatory){
                    return [
                        'data' => $data,
                        'message' => 'Signatory record not found',
                        'info' => 'Unable to locate the corresponding signatory record for this request. Please refresh or check your permissions.',
                        'status' => false
                    ];
                }
            }

            switch ($request->status_id) {
                case 25:
                    $signatory->update([
                        'recommended_id'   => $user?->id,
                        'recommended_date' => now(),
                        'recommended_by'   => $this->image($request, $signatoryId),
                        'status_id'        => $request->status_id,
                    ]);
                    $type = 'recommended';
                break;
                case 26:
                    $signatory->update([
                        'approved_id'   => $user?->id,
                        'approved_date' => now(),
                        'approved_by'   => $this->image($request, $signatoryId),
                        'status_id'     => $request->status_id,
                        'is_completed'  => 1,
                    ]);

                    if ($data->type_id == 165) {
                        $this->overtime($data->id);
                    }
                    $type = 'approved';
                break;
                case 30: 
                    $signatory->update([
                        'is_disapproved' => 1,
                        'disapproved_id' => $user?->id,
                        'status_id'      => $request->status_id,
                    ]);

                    $data->comments()->create([
                        'user_id' => auth()->id(),
                        'content' => $request->reason,
                    ]);

                    if ($data->type_id == 158) {
                        $this->leave($data->id);
                    }
                    $type = 'disapproved';
                break;
            }

            $signatory->statusable()->create([
                'user_id'   => auth()->id(),
                'status_id' => $request->status_id,
            ]);

            $this->updateSignatory($requestId,$signatoryId,$type,$user->is_designated);
 
            return [
                'data' => $data,
                'message' => 'Request Status Updated',
                'info' => 'The status of this request has been successfully updated. Please check your notifications for the latest details and next steps.',
            ];

        } catch (\Exception $e) {
            return [
                'data' => null,
                'message' => 'Update Failed',
                'info' => 'An error occurred while updating the request status. Please try again later or contact your administrator. (' . $e->getMessage() . ')',
            ];
        }
    }

    public function leave($id){
        $data = RequestLeave::with('credits.log')->where('request_id',$id)->first();
        $credits = $data->credits;
        foreach($credits as $credit){
            $log = CreditLog::where('id',$credit->log_id)->first();
            $user = UserCredit::where('id',$log->credit_id)->first();
            $old_balance = $user->balance;
            $user->balance += $log->amount;
            $user->used -= $log->amount;
            if($user->save()){
                $log = $user->logs()->create([
                    'amount' => $log->amount,
                    'old_balance' => $old_balance,
                    'new_balance' => $user->balance,
                    'remarks' => 'Return of leave credits for cancelled/disapproved leave.',
                    'is_automated' => 1,
                    'user_id' => 1,
                    'type_id' => 164
                ]);

                if($log){
                    $data->credits()->create([
                        'is_borrowed' => $credit->is_borrowed,
                        'is_returned' => 1,
                        'log_id' => $log->id,
                        'credit_id' => $credit->credit_id
                    ]);
                }
            }
        }
    }

    public function overtime($id){
        $data = new RequestOvertime;
        $data->code = $this->generateCode();
        $data->request_id = $id;
        $data->status_id = 31;
        $data->save();
    }

    private function image($request,$id){
        $image = $request->input('photo'); // base64 string

        // Validate format
        if (!preg_match('/^data:image\/(\w+);base64,/', $image, $matches)) {
            return response()->json(['error' => 'Invalid image format.'], 422);
        }

        $type = strtolower($matches[1]); // png, jpg, jpeg, gif
        if (!in_array($type, ['jpg', 'jpeg', 'png'])) {
            return response()->json(['error' => 'Invalid image type.'], 422);
        }

        // Remove header and decode
        $image = substr($image, strpos($image, ',') + 1);
        $image = str_replace(' ', '+', $image);
        $imageData = base64_decode($image);

        if ($imageData === false) {
            return response()->json(['error' => 'Base64 decode failed.'], 422);
        }

        // Save to storage/app/public/images/attendance
        $filename = $id.Str::random(10) . '.' . $type;
        $path = 'images/signatory/' . $filename;
        Storage::disk('public')->put($path, $imageData);

        return $path;
    }

    private function generateCode()
    {
        return \DB::transaction(function () {
            $latest = RequestOvertime::lockForUpdate()
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->orderByDesc('id')
                ->first();

            $count = $latest
                ? (int) substr($latest->code, -4) + 1
                : 1;

            $code = now()->format('Y') . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
            return $code;
        });
    }

    private function updateSignatory($requestId,$id,$type,$is_designated)
    {
        $report = RequestReport::where('request_id', $requestId)->first();
        $information = json_decode($report->information, true);

        $signatory = RequestSignatory::
        with('division','approved.user.profile:user_id,firstname,middlename,lastname,suffix_id,signature','recommended.user.profile:user_id,firstname,middlename,lastname,suffix_id,signature')
        ->where('id',$id)->first();
     
        foreach ($information['signatories'] as $key => $sign) {
            if ($sign['code'] === $signatory->code ) {
                $signatoriesFormatted = [
                    'code' => $signatory->code,
                    'division' => $signatory->division->name ?? 'n/a',
                    'division_id' => $signatory->division->id ?? null,
                    'recommended' => [
                        'name' => $signatory->recommended?->user?->profile?->fullname,
                        'signature' => $signatory->recommended?->user?->profile?->signature,
                        'date' => ($signatory->recommended_date) ? $signatory->recommended_date : null
                    ],
                    'approved' => [
                        'name' => $signatory->approved?->user?->profile?->fullname,
                        'signature' => $signatory->approved?->user?->profile?->signature,
                        'date' => ($signatory->approved_date) ? $signatory->approved_date : null
                    ]
                ];
                if($type == 'recommended'){
                    $role = ($is_designated) ? 'Assistant Regional Director ('.$signatory->division->others.')' : 'OIC - '.'Assistant Regional Director ('.$signatory->division->others.')';
                    $signatoriesFormatted['recommended']['role'] = $role;
                }else{
                    $role = ($is_designated) ? '' : 'OIC - '.'Regional Director';
                    $signatoriesFormatted['approved']['role'] = $role;
                }
                $information['signatories'][$key] = $signatoriesFormatted;
                break; 
            }
        }
        $report->information = json_encode($information);
        $report->save();

        return true;
    }
}
