<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OrgChart;
use App\Mail\AccountActivationCode;
use Illuminate\Support\Facades\Mail;
use App\Http\Resources\Trace\Signatory\DesignationResource;

class InfoController extends Controller
{
    public function keyofficials(){
        return inertia('Modules/Others/Organization/Index',[
            'designations' => $this->designations()
        ]);
    }

    /**
     * Renders the raw Blade view with dummy data so the design can be
     * iterated on in the browser without sending any mail.
     */
    public function mailing(){
        return view('emails.account-activation', $this->mailingDummyData());
    }

    /**
     * Actually sends the activation email so it can be checked in a real
     * inbox. Bypasses the mailable's ShouldQueue (via sendNow) so it goes
     * out immediately without needing a queue worker running. Restricted
     * to non-production so this can't be used as an open mail relay.
     */
    public function mailingTest(Request $request){
        if (app()->environment('production')) {
            abort(404);
        }

        $email = $request->query('email', 'rjumli.dost9@gmail.com');
        $data = $this->mailingDummyData();

        Mail::to($email)->sendNow(new AccountActivationCode($data['user'], $data['code']));

        return "Test activation email sent to {$email}.";
    }

    private function mailingDummyData(){
        return [
            'user' => (object) ['username' => 'juan.delacruz'],
            'code' => '482913',
        ];
    }

    private function designations(){
        $data = OrgChart::with('designation','assigned')
        ->with([
            // 'designationable.schedules.user:id,email,username',
            // 'designationable.schedules.user.profile:user_id,firstname,middlename,lastname,suffix_id,avatar',
            'designationable.schedules' => function ($q) {
                $q->where('is_completed', 0)
                  ->whereIn('is_ongoing', [0, 1])
                  ->where('is_designated', 0)
                  ->whereDate('end_at', '>=', now()->toDateString())
                  ->with([
                      'user:id,email,username',
                      'user.profile:user_id,firstname,middlename,lastname,suffix_id,avatar',
                  ]);
            },
            'designationable.user:id,email,username',
            'designationable.user.profile:user_id,firstname,middlename,lastname,suffix_id,avatar',
            'designationable.oic:id,email,username',
            'designationable.oic.profile:user_id,firstname,middlename,lastname,suffix_id,avatar'
        ])
        ->with('user:id,email,username','user.profile:user_id,firstname,middlename,lastname,suffix_id,avatar','oic:id,email,username','oic.profile:user_id,firstname,middlename,lastname,suffix_id,avatar')
        ->orderBy('order','ASC')
        ->get();
        return DesignationResource::collection($data);
    }
}
