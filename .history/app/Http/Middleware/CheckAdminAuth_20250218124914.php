<?php

namespace App\Http\Middleware;

use Closure;
use Inertia\Middleware;
use Illuminate\Support\Facades\Auth;
use Request;

class CheckAdminAuth extends Middleware
{
    public function handle(Request $request, Closure $next)
    {
        // Ellenőrizd, hogy a felhasználó be van-e jelentkezve az 'admin' guarddal
        if (Auth::guard('admin')->check()) {
            return $next($request);
        }

        // Ha nincs bejelentkezve, akkor átirányítás a bejelentkezési oldalra
        return redirect()->route('admin.login');
    }

    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
            ],
            'ziggy' => fn () => [
                ...(new Ziggy)->toArray(),
                'location' => $request->url(),
            ],
        ];
    }
}

?>