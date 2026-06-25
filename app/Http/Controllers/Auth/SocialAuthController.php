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
use Illuminate\Validation\ValidationException;

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

        if(!$user) {
            $email = strtolower($socialUser->getEmail());
            $kradworkz = hash('sha256', $email);
            $user = User::where('kradworkz', $kradworkz)->first();
            
           
            if ($user) {
                if($user->is_active) {
                    do{
                        $code = random_int(100000000, 999999999); // 9 digits
                    } while (\App\Models\User::where('code', $code)->exists());

                    $user->update(['email_verified_at' => now(), 'code' => $code]);
                    Mail::to($user->email)->queue(new AccountActivationCode($user, $code));

                    $user->update([
                        'provider'    => $provider,
                        'provider_id' => $socialUser->getId()
                    ]);
                }else{
                   return redirect('/login')->withErrors('Unable to login.');
                }
            } else {
                return redirect('/login')->withErrors('Unable to login.');
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
