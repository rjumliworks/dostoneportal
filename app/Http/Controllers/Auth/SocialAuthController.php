<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Mail\AccountActivationCode; 
use Illuminate\Support\Facades\Mail;

class SocialAuthController extends Controller
{
    public function redirect(string $provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback(string $provider)
    {
        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            return redirect('/login')->withErrors('Unable to login.');
        }

        $user = User::where('provider', $provider)
            ->where('provider_id', $socialUser->getId())
            ->first();

        if (!$user) {
            $email = strtolower($socialUser->getEmail());
            $kradworkz = hash('sha256', $email);
            $user = User::where('kradworkz', $kradworkz)->first();
            
           
            if ($user) {
                do{
                    $code = random_int(100000000, 999999999); // 9 digits
                } while (\App\Models\User::where('code', $code)->exists());

                $user->update(['email_verified_at' => now(), 'code' => $code]);
                Mail::to($user->email)->queue(new AccountActivationCode($user, $code));
                // Link existing account
                $user->update([
                    'provider'    => $provider,
                    'provider_id' => $socialUser->getId(),
                    // 'avatar'      => $socialUser->getAvatar(),
                ]);
            } else {
                return redirect('/login')->withErrors('Unable to login.');
                // Create new user
                // $user = User::create([
                //     'name'        => $socialUser->getName() ?? $socialUser->getNickname(),
                //     'username'    => $socialUser->getEmail(),
                //     'email'       => $socialUser->getEmail(),
                //     'password'    => bcrypt(Str::random(16)),
                //     'provider'    => $provider,
                //     'provider_id' => $socialUser->getId(),
                //     'role'      => 'Photographer',
                //     'email_verified_at' => now()
                // ]);
                // $fullName = $socialUser->getName() ?? $socialUser->getNickname() ?? '';
                // $name = $this->splitFirstLast($fullName);
                // if($user){
                //     UserProfile::create([
                //         'firstname' => $name['first_name'],
                //         'lastname'  => $name['last_name'],
                //     ]);
                // }
            }
        }

        Auth::login($user, true);

        return redirect()->intended('/dashboard');
    }

    private function splitFirstLast(string $fullName): array
    {
        $parts = preg_split('/\s+/', trim($fullName));

        $firstName = $parts[0] ?? null;
        $lastName  = count($parts) > 1 ? end($parts) : null;

        return [
            'first_name' => $firstName,
            'last_name'  => $lastName,
        ];
    }
}
