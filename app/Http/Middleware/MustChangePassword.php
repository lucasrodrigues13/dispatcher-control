<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class MustChangePassword
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && auth()->user()->must_change_password) {
            // Just show the warning but allow navigation
            if (!session()->has('warning')) {
                session()->flash('warning', 'Your password is still the default one. Please change it as soon as possible for security reasons.');
            }
        }

        return $next($request);
    }
}
