<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        // Hit cross-origin, unauthenticated, from the separate
        // FaceRecognitionPage.jsx display app (see VipController::signal())
        // - it has no session/CSRF cookie for this domain to send.
        'vip-signal',
    ];
}
