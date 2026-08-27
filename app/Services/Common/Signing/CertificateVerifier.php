<?php

namespace App\Services\Common\Signing;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class CertificateVerifier
{
    /**
     * Verify a PNPKI (.p12) certificate password against the stored certificate
     * file by delegating to the local certificate-signing service.
     *
     * @return array{checked: bool, valid: bool, message: string|null}
     */
    public function verify(string $s3Path, string $password): array
    {
        try {
            $p12Content = Storage::disk('s3')->get($s3Path);
        } catch (\Throwable $e) {
            return ['checked' => false, 'valid' => true, 'message' => null];
        }

        if ($p12Content === null) {
            return ['checked' => false, 'valid' => true, 'message' => null];
        }

        $tempDir = storage_path('app/temp');
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $tempPath = $tempDir . '/' . uniqid('p12_') . '.p12';
        file_put_contents($tempPath, $p12Content);

        try {
            $response = Http::asForm()->timeout(15)->post('http://127.0.0.1:8000/verify-p12', [
                'p12_file' => $tempPath,
                'p12_pass' => $password,
            ]);
        } catch (\Throwable $e) {
            @unlink($tempPath);
            // Verification service unreachable: don't block the user on it.
            return ['checked' => false, 'valid' => true, 'message' => null];
        }

        @unlink($tempPath);

        if (!$response->successful()) {
            return ['checked' => false, 'valid' => true, 'message' => null];
        }

        $result = $response->json();

        return [
            'checked' => true,
            'valid' => (bool) ($result['valid'] ?? false),
            'message' => $result['message'] ?? null,
        ];
    }
}
