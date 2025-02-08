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
            'marker_color' => [
                'sometimes',
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    $allowedColors = ['red', 'green', 'blue', 'yellow', 'black'];
                    $hexColorPattern = '/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/';
                    
                    if (
                        !in_array(strtolower($value), $allowedColors) &&
                        !preg_match($hexColorPattern, $value)
                    ) {
                        $fail("The $attribute must be a valid color name or hex code.");
                    }
                },
            ]
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
