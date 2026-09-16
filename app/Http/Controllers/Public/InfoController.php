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
    private const COMMITTEE_TYPES = [
        'bac' => ['type' => 'BAC', 'label' => 'Bids and Awards Committee', 'per_station' => false],
        'twg' => ['type' => 'TWG', 'label' => 'Technical Working Group', 'per_station' => false],
        'iar' => ['type' => 'IAR', 'label' => 'Inspection and Acceptance Committee', 'per_station' => true],
    ];

    public function keyofficials($group = null){
        if ($group && isset(self::COMMITTEE_TYPES[$group])) {
            return inertia('Modules/Others/Organization/Index',[
                'group' => $group,
                'committee' => $this->committee($group),
            ]);
        }

        return inertia('Modules/Others/Organization/Index',[
            'group' => 'top-management',
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
        $committeeTypes = array_column(self::COMMITTEE_TYPES, 'type');

        $data = $this->baseDesignationQuery()
        ->whereHas('designation', fn ($q) => $q->whereNotIn('type', $committeeTypes))
        ->orderBy('order','ASC')
        ->get();
        return DesignationResource::collection($data);
    }

    private function baseDesignationQuery(){
        return OrgChart::with('designation','assigned')
        ->with([
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
        ->with('user:id,email,username','user.profile:user_id,firstname,middlename,lastname,suffix_id,avatar','oic:id,email,username','oic.profile:user_id,firstname,middlename,lastname,suffix_id,avatar');
    }

    /**
     * Groups a committee's OrgChart slots by role (Chairperson / Vice Chairperson / Members),
     * further splitting Members by assigned station for committees like IAR that seat
     * members per region/province instead of as one agency-wide pool.
     */
    private function committee(string $group): array
    {
        $config = self::COMMITTEE_TYPES[$group];

        $rows = $this->baseDesignationQuery()
            ->whereHas('designation', fn ($q) => $q->where('type', $config['type']))
            ->orderBy('order', 'ASC')
            ->orderBy('assigned_id', 'ASC')
            ->get();

        $chairperson = $rows->first(fn ($row) => $row->designation->name === 'Chairperson');
        $viceChairperson = $rows->first(fn ($row) => $row->designation->name === 'Vice Chairperson');
        $members = $rows->filter(fn ($row) => $row->designation->name === 'Member')->values();

        $result = [
            'label' => $config['label'],
            'chairperson' => $chairperson ? (new DesignationResource($chairperson))->resolve() : null,
            'vice_chairperson' => $viceChairperson ? (new DesignationResource($viceChairperson))->resolve() : null,
        ];

        if ($config['per_station']) {
            $result['stations'] = $members
                ->groupBy(fn ($row) => $row->assigned->name)
                ->map(fn ($stationMembers, $station) => [
                    'station' => $station,
                    'members' => DesignationResource::collection($stationMembers->values())->resolve(),
                ])
                ->values();
        } else {
            $result['members'] = DesignationResource::collection($members)->resolve();
        }

        return $result;
    }
}
