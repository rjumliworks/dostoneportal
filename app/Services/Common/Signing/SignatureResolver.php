<?php

namespace App\Services\Common\Signing;

use App\Models\User;
use Illuminate\Support\Facades\Storage;

class SignatureResolver
{
    /**
     * Resolve a user's e-signature image for embedding in a printed PDF.
     *
     * Signatures now live in user_certificates.signature (uploaded to S3)
     * instead of the legacy user_profiles.signature column. Once PNPKI (.p12)
     * signing is wired in, a user with a certificate file on file should be
     * routed through that flow instead; until then this image is the single
     * fallback for everyone, checked or not.
     */
    public function resolve(?User $user): ?string
    {
        return $this->resolvePath($user?->certificate?->signature);
    }

    /**
     * Resolve a raw user_certificates.signature S3 key into an embeddable
     * base64 data URI. Dompdf can't fetch remote images (enable_remote is
     * off), so the file has to be pulled down and inlined.
     */
    public function resolvePath(?string $path): ?string
    {
        if (!$path) {
            return null;
        }

        try {
            if (!Storage::disk('s3')->exists($path)) {
                return null;
            }

            $contents = Storage::disk('s3')->get($path);
            $mime = Storage::disk('s3')->mimeType($path) ?: 'image/png';

            return 'data:' . $mime . ';base64,' . base64_encode($contents);
        } catch (\Throwable $e) {
            return null;
        }
    }
}
