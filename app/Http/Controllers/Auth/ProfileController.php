<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\UserInformation;
use App\Models\UserCertificate;
use App\Models\UserAddress;
use App\Models\UserAcademic;
use App\Models\UserContract;
use App\Models\UserEligibility;
use App\Models\UserWorkExperience;
use App\Models\UserVoluntaryWork;
use App\Models\UserTraining;
use App\Models\UserOtherInformation;
use App\Models\UserReference;
use App\Models\UserPdsDeclaration;
use App\Traits\HandlesTransaction;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\Profile\ViewClass;
use App\Services\Profile\SaveClass;
use App\Services\Profile\PrintClass;
use App\Services\DropdownClass;
use App\Http\Requests\Auth\ProfileRequest;
use App\Http\Requests\Auth\PdsRequest;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;
use App\Mail\AccountActivationCode;
use Illuminate\Support\Facades\Mail;

class ProfileController extends Controller
{
    use HandlesTransaction;

    public $view, $save, $dropdown, $print;

    public function __construct(ViewClass $view, SaveClass $save, DropdownClass $dropdown, PrintClass $print){
        $this->view = $view;
        $this->save = $save;
        $this->dropdown = $dropdown;
        $this->print = $print;
    }

    public function index(Request $request){
        $options = $request->option;
        switch($options){
            case 'authentication-logs':
                return $this->view->authenticationlogs($request);
            break;
            case 'activity-logs':
                return $this->view->activitylogs($request);
            break;
            case 'statistics':
                return $this->view->statistics($request);
            break;
            case 'sessions':
                return $this->view->sessions($request);
            break;
            case 'download':
                return $this->print->pds(\Auth::id());
            break;
            default:
            return inertia('Auth/Profile/Index', $this->loadProfileData(\Auth::user()->id));
        }
    }

    public function onboarding(){
        return response()->json($this->loadProfileData(\Auth::user()->id));
    }

    private function loadProfileData($userId){
        $profile = User::with('profile')->find($userId)->profile;
        $information = UserInformation::where('user_id',$userId)->first();
        $personal = $information->personal ?? [];

        return [
            // Built explicitly (not the raw model) because UserProfile decrypts several
            // columns via an overridden getAttribute(), which plain toArray()/json
            // serialization bypasses, leaking ciphertext instead of the real value.
            // height/weight/citizenship*/place_of_birth/agency_employee_no live in
            // user_information.personal (JSON), but are merged into 'profile' here
            // so the wizard's Personal Information step can read them from one place.
            'profile' => $profile ? [
                'sex_id' => $profile->sex_id,
                'marital_id' => $profile->marital_id,
                'religion_id' => $profile->religion_id,
                'blood_id' => $profile->blood_id,
                'mobile' => $profile->mobile,
                'birthdate' => $profile->birthdate,
                'height' => $personal['height'] ?? null,
                'weight' => $personal['weight'] ?? null,
                'citizenship' => $personal['citizenship'] ?? null,
                'citizenship_type' => $personal['citizenship_type'] ?? null,
                'citizenship_country' => $personal['citizenship_country'] ?? null,
                'place_of_birth' => $personal['place_of_birth'] ?? null,
                'agency_employee_no' => $personal['agency_employee_no'] ?? null,
            ] : null,
            'addresses' => UserAddress::with('region','province','municipality','barangay')->where('user_id',$userId)->get(),
            'academics' => UserAcademic::with('school','course','level')->where('user_id',$userId)->orderByDesc('id')->get(),
            'eligibilities' => UserEligibility::where('user_id',$userId)->orderByDesc('id')->get(),
            'contracts' => UserContract::with('position','type')->where('user_id',$userId)->orderByDesc('start_at')->get(),
            'workExperiences' => UserWorkExperience::where('user_id',$userId)->orderByDesc('start_at')->get(),
            'voluntaryWorks' => UserVoluntaryWork::where('user_id',$userId)->orderByDesc('start_at')->get(),
            'trainings' => UserTraining::where('user_id',$userId)->orderByDesc('start_at')->get(),
            'otherInformation' => UserOtherInformation::where('user_id',$userId)->get(),
            'references' => UserReference::where('user_id',$userId)->get(),
            'declaration' => UserPdsDeclaration::where('user_id',$userId)->first(),
            'userInformation' => $information,
            'dropdowns' => [
                'levels' => $this->dropdown->datas('Level'),
            ],
        ];
    }

    public function security(){
        return inertia('Auth/Profile/Security/Index');
    }

    public function pds(PdsRequest $request, $id = null){
        if ($id) {
            $request->merge(['id' => $id]);
        }
        $result = $this->handleTransaction(function () use ($request) {
            switch ($request->option) {
                case 'declaration':
                    return $this->save->declaration($request);
                case 'government_ids':
                    return $this->save->governmentIds($request);
                case 'family_background':
                    return $this->save->familyBackground($request);
                default:
                    return $this->save->pds($request);
            }
        });

        return back()->with([
            'data' => $result['data'] ?? [],
            'message' => $result['message'],
            'info' => $result['info'] ?? '',
        ]);
    }

    public function destroyPds(Request $request, $id){
        $result = $this->save->removePds($request, $id);

        return back()->with([
            'message' => $result['message'],
        ]);
    }

    public function store(Request $request)
    {
        if($request->option == 'certificate'){
           
            $request->validate([
                'p12' => 'required|file'
            ]);
            if ($request->file('p12')->getClientOriginalExtension() !== 'p12') {
                return back()->withErrors(['p12' => 'The uploaded file must have a .p12 extension.']);
            }
            $result = $this->handleTransaction(function () use ($request) {

                $user = User::find(\Auth::user()->id);
                // Get the uploaded file
                $file = $request->file('p12');

                // Optional: generate a unique filename
                $filename = 'oneportal/certificates/' . $user->username . '.' . $file->getClientOriginalExtension();

                // Store in S3
                $path = $file->storeAs('', $filename, 's3');

                // Get full URL if needed
                $url = Storage::disk('s3')->url($path);

                // Find or create the UserCertificate
                $certificate = UserCertificate::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'file' => $path, // save the S3 path
                        'password' => 'rstlD057rubber',
                    ]
                );

                   return [
                        'data' => [],
                        'message' => 'Profile picture updated successfully.', 
                        'info' => "The user's profile image has been changed to the new photo."
                    ];
            });
        }else if($request->option == 'signature'){
            $request->validate([
                'signature' => 'required'
            ]);
           
            $result = $this->handleTransaction(function () use ($request) {

                $user = User::find(\Auth::user()->id);
                // Get the uploaded file
                $file = $request->file('signature');

                // Optional: generate a unique filename
                $filename = 'oneportal/signatures/' . $user->username . '.' . $file->getClientOriginalExtension();

                // Store in S3
                $path = $file->storeAs('', $filename, 's3');

                // Get full URL if needed
                $url = Storage::disk('s3')->url($path);

                // Find or create the UserCertificate
                $certificate = UserCertificate::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'signature' => $path
                    ]
                );

                   return [
                        'data' => [],
                        'message' => 'Profile picture updated successfully.', 
                        'info' => "The user's profile image has been changed to the new photo."
                    ];
            });
        }else{
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png|max:2048' // Assuming maximum file size is 2MB
            ],[
                'image.required' => 'Please upload an image.',
                'image.image' => 'The file must be a valid image.',
                'image.mimes' => 'Only JPEG or PNG images are allowed.',
                'image.max' => 'The image size must be less than 2MB.',
            ]);
            $result = $this->handleTransaction(function () use ($request) {
                return $this->save->save($request);
            });

            return back()->with([
                'data' => $result['data'],
                'message' => $result['message'],
                'info' => $result['info'],
                'status' => $result['status'],
            ]);
        }
    }

    public function update( ProfileRequest $request){
        $result = $this->handleTransaction(function () use ($request) {
            return $this->save->update($request);
        });

        return back()->with([
            'data' => $result['data'],
            'message' => $result['message'],
            'info' => $result['info'],
            'status' => $result['status'],
        ]);
    }

    public function destroy(Request $request)
    {
        return $this->save->destroy($request);
    }

    public function activation(){
        return inertia('Auth/Activation');
    }

    public function activate(Request $request){
        $validated = $request->validate([
            'code' => ['required', 'digits:9'],
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->mixedCase()     // must include uppercase and lowercase
                    ->letters()       // must include letters
                    ->numbers()       // must include numbers
                    ->symbols()       // must include symbols
                    ->uncompromised() // checks against data leaks (optional)
            ],
        ]);
        $id = \Auth::user()->id;
        $user = User::findOrFail($id);
        if ($user->code !== $request->code) {
            throw ValidationException::withMessages([
                'code' => 'The activation code you entered is invalid.',
            ]);
        }
        $user->is_active = 1;
        $user->must_change = 0;
        $user->password = bcrypt($validated['password']);
        $user->password_changed_at = now();
        if($user->save()){
            return redirect()->intended(route('dashboard', absolute: false));
        }
    }

    public function check(Request $request)
    {
        $request->validate([
        'code' => 'required|string|size:9',
        ]);

        $user = \Auth::user();
        $valid = $user->code === $request->code;

        return response()->json([
            'valid' => $valid,
        ]);
    }

    public function otp(Request $request){
        $user = User::where('id',$request->id)->first();
        $code = random_int(100000000, 999999999); // 9 digits
        $user->update(['code' => $code]);
        Mail::to($user->email)->queue(new AccountActivationCode($user, $code));
         return response()->json([
            'success' => true,
            'message' => 'Verification code sent.'
        ]);
    }

}
