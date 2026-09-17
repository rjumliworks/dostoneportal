<?php

namespace App\Services\Profile;

use App\Models\User;
use App\Models\UserInformation;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class SaveClass
{
    public function save($request){

        $user = User::find(\Auth::user()->id);
        $image = $request->file('image');
        $manager = new ImageManager(new Driver());

        // Read image
        $img = $manager->read($image);

        // Resize + convert to webp
        $img->cover(300, 300); // better than fit() in v3
        $webp = $img->toWebp(80);

        $filename = $user->username.'.webp';
        $s3Path = $image->storeAs('oneportal/avatars', $filename, 's3');
        
        $user->profile->avatar = $s3Path;
        $user->profile->save();

        return [
            'data' => [],
            'status' => true,
            'message' => 'Profile picture updated successfully.',
            'info' => "The user's profile image has been changed to the new photo."
        ];
    }

    public function update($request){
        $user = User::find(\Auth::user()->id);

        switch ($request->option) {
            case 'personal':
                $this->updateProfileFields($request, $user);
                break;
            case 'address':
                $this->updateAddresses($request, $user);
                break;
            default:
                $this->updateProfileFields($request, $user);
                $this->updateAddresses($request, $user);
                break;
        }

        $data = User::find(\Auth::user()->id);
        return [
            'data' => $data,
            'status' => true,
            'message' => 'User information updated successfully.',
            'info' => "All relevant fields have been refreshed with the latest data."
        ];
    }

    private function updateProfileFields($request, $user)
    {
        $profile = $user->profile;
        $profile->sex_id = $request->sex_id;
        $profile->blood_id = $request->blood_id;
        $profile->marital_id = $request->marital_id;
        $profile->religion_id = $request->religion_id;
        $profile->mobile = $request->mobile;
        $profile->birthdate = $request->birthdate;
        $profile->save();

        UserInformation::updateOrCreate(
            ['user_id' => $user->id],
            ['personal' => [
                'height' => $request->height,
                'weight' => $request->weight,
                'citizenship' => $request->citizenship,
                'citizenship_type' => $request->citizenship_type,
                'citizenship_country' => $request->citizenship_country,
                'place_of_birth' => $request->place_of_birth,
                'agency_employee_no' => $request->agency_employee_no,
            ]]
        );
    }

    private function updateAddresses($request, $user)
    {
        if ($request->filled('permanent.address')) {
            $user->addresses()->updateOrCreate(
                [
                    'is_permanent' => 1,
                ],
                [
                    'address'           => $request->permanent['address'],
                    'zip_code'          => $request->permanent['zip_code'] ?? null,
                    'region_code'       => $request->permanent['region_code'],
                    'province_code'     => $request->permanent['province_code'],
                    'municipality_code' => $request->permanent['municipality_code'],
                    'barangay_code'     => $request->permanent['barangay_code'],
                    'latitude'          => $request->permanent['latitude'],
                    'longitude'         => $request->permanent['longitude'],
                ]
            );
        }

        if ($request->filled('home.address')) {
            $user->addresses()->updateOrCreate(
                [
                    'is_permanent' => 0,
                ],[
                    'address'           => $request->home['address'],
                    'zip_code'          => $request->home['zip_code'] ?? null,
                    'region_code'       => $request->home['region_code'],
                    'province_code'     => $request->home['province_code'],
                    'municipality_code' => $request->home['municipality_code'],
                    'barangay_code'     => $request->home['barangay_code'],
                    'latitude'          => $request->home['latitude'],
                    'longitude'         => $request->home['longitude']
                ]
            );
        }
    }

    public function storeSchool($request)
    {
        $school = \App\Models\ListAcademic::firstOrCreate(
            ['name' => trim($request->name), 'type_id' => 173],
            ['user_id' => \Auth::id()]
        );

        return [
            'data' => [
                'value' => $school->id,
                'name' => $school->name,
            ],
            'status' => true,
            'message' => 'School added successfully.',
            'info' => 'You can now select it from the list.',
        ];
    }

    public function pds($request)
    {
        $map = [
            'academic' => \App\Models\UserAcademic::class,
            'eligibility' => \App\Models\UserEligibility::class,
            'work_experience' => \App\Models\UserWorkExperience::class,
            'voluntary_work' => \App\Models\UserVoluntaryWork::class,
            'training' => \App\Models\UserTraining::class,
            'other_information' => \App\Models\UserOtherInformation::class,
            'reference' => \App\Models\UserReference::class,
        ];

        $model = $map[$request->option];
        $payload = $request->except(['option', 'id']);
        $payload['user_id'] = \Auth::id();

        // Elementary, Junior High School, and Secondary don't have a degree/course —
        // fall back to a "Not Available" list_academics record (type_id 174) instead
        // of leaving it blank, since course_id is a required, non-nullable foreign
        // key. Matched by name, never a hardcoded list_data id — see PdsRequest.
        if ($request->option === 'academic' && empty($payload['course_id'])) {
            $levelName = \App\Models\ListData::find($payload['level_id'] ?? null)?->name ?? '';

            if (in_array($levelName, ['Elementary', 'Junior High School', 'Secondary'], true)) {
                $payload['course_id'] = \App\Models\ListAcademic::firstOrCreate(
                    ['name' => 'Not Available', 'type_id' => 174]
                )->id;
            }
        }

        // exam_name is kept as a denormalized copy of the selected exam_id's label so
        // older reports/views reading that column keep working unchanged — except for
        // the "Other Board / Bar Examination" catch-all, where it isn't in the list at
        // all and exam_name instead holds whatever the user typed for it.
        if ($request->option === 'eligibility') {
            $typeName = !empty($payload['type_id']) ? \App\Models\ListData::find($payload['type_id'])?->name : null;
            $isOtherBoard = $typeName && str_contains(strtolower($typeName), 'other board');

            if (!$isOtherBoard || empty($payload['exam_name'])) {
                $payload['exam_name'] = \App\Models\ListData::find($payload['exam_id'])?->name;
            }
        }

        if ($request->filled('id')) {
            $record = $model::where('id', $request->id)->where('user_id', \Auth::id())->firstOrFail();
            $record->update($payload);
        } else {
            $record = $model::create($payload);
        }

        return [
            'data' => $record,
            'status' => true,
            'message' => 'Record saved successfully.',
            'info' => 'Your Personal Data Sheet has been updated.',
        ];
    }

    public function declaration($request)
    {
        $record = \App\Models\UserPdsDeclaration::updateOrCreate(
            ['user_id' => \Auth::id()],
            array_merge($request->except('option'), ['declared_at' => now()->toDateString()])
        );

        // Declaration is the final wizard step, so completing it is what marks the PDS as done.
        \App\Models\UserProfile::where('user_id', \Auth::id())->update(['is_completed' => 1]);

        return [
            'data' => $record,
            'status' => true,
            'message' => 'Your Personal Data Sheet has been completed.',
            'info' => 'This is now your official PDS record on file. Please keep it updated — it will be used to automatically generate your Personal Data Sheet (CS Form No. 212) whenever one is needed.',
        ];
    }

    public function governmentIds($request)
    {
        $record = UserInformation::updateOrCreate(
            ['user_id' => \Auth::id()],
            ['accounts' => $request->input('accounts')]
        );

        return [
            'data' => $record,
            'status' => true,
            'message' => 'Government ID numbers saved successfully.',
            'info' => 'Your Personal Data Sheet has been updated.',
        ];
    }

    public function familyBackground($request)
    {
        $record = UserInformation::updateOrCreate(
            ['user_id' => \Auth::id()],
            ['backgrounds' => [
                'parents'  => $request->input('parents'),
                'spouse'   => $request->input('spouse'),
                'children' => $request->input('children', []),
            ]]
        );

        return [
            'data' => $record,
            'status' => true,
            'message' => 'Family background saved successfully.',
            'info' => 'Your Personal Data Sheet has been updated.',
        ];
    }

    public function removePds($request, $id)
    {
        $map = [
            'academic' => \App\Models\UserAcademic::class,
            'eligibility' => \App\Models\UserEligibility::class,
            'work_experience' => \App\Models\UserWorkExperience::class,
            'voluntary_work' => \App\Models\UserVoluntaryWork::class,
            'training' => \App\Models\UserTraining::class,
            'other_information' => \App\Models\UserOtherInformation::class,
            'reference' => \App\Models\UserReference::class,
        ];

        $model = $map[$request->option];
        $model::where('id', $id)->where('user_id', \Auth::id())->delete();

        return [
            'status' => true,
            'message' => 'Record removed successfully.',
        ];
    }

    public function destroy($request)
    {
        if (!Auth::guard('web')->validate([
            'email' => $request->user()->email,
            'password' => $request->password,
        ])) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }
        $this->deleteOtherSessionRecords($request);
        return back(303);
    }

    protected function deleteOtherSessionRecords(Request $request)
    {
        if (config('session.driver') !== 'database') {
            return;
        }
        \DB::connection(config('session.connection'))->table(config('session.table', 'sessions'))
            ->where('user_id', $request->user()->getAuthIdentifier())
            ->where('id', '!=', $request->session()->getId())
            ->delete();
    }

}
