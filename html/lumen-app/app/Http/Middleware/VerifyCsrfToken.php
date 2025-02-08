<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Contracts\Encryption\Encrypter;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    public function __construct(Encrypter $encrypter)
    {
        parent::__construct($encrypter);
    }

    public function handle($request, Closure $next)
    {
        // CSRF kontrolünü devre dışı bırakılacak route'ları buraya ekleyin
        $except = [
            // '/api/route-to-exclude-csrf'
        ];

        if (in_array($request->path(), $except)) {
            return $next($request);
        }

        return parent::handle($request, $next);
    }
}