<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Validator;

class ValidateRequest
{
    public function handle($request, Closure $next)
    {
        $validator = Validator::make($request->all(), [
            'latitude' => 'sometimes|required|numeric|between:-90,90',
            'longitude' => 'sometimes|required|numeric|between:-180,180',
            'location_name' => 'sometimes|required|string|max:255',
            'marker_color' => 'sometimes|required|string|in:red,green,blue,yellow,black'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400);  // 400 Bad Request
        }

        // Eğer geçerli ise işlemi devam ettir
        return $next($request);
    }
}
