<?php

namespace App\Auth;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable;

/**
 * App\Models\User stores `email` encrypted at rest (see User::setEmailAttribute),
 * with `kradworkz` holding a sha256 hash of the lowercased plaintext for exact-match
 * lookups. The stock EloquentUserProvider queries `where('email', $value)` directly,
 * which can never match the ciphertext — breaking anything that resolves a user by
 * email credentials, such as Laravel's password-reset broker (Password::sendResetLink
 * / Password::reset). This provider rewrites an `email` credential into the equivalent
 * `kradworkz` lookup before delegating to the parent implementation.
 */
class EncryptedEmailUserProvider extends EloquentUserProvider
{
    public function retrieveByCredentials(#[\SensitiveParameter] array $credentials): ?Authenticatable
    {
        if (array_key_exists('email', $credentials)) {
            $email = $credentials['email'];
            unset($credentials['email']);
            $credentials['kradworkz'] = hash('sha256', strtolower($email));
        }

        return parent::retrieveByCredentials($credentials);
    }
}
