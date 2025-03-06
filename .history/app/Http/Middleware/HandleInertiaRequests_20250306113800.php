<?php

namespace App\Http\Middleware;

use Auth;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Tighten\Ziggy\Ziggy;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => $request->user(),
                'admin' => Auth::guard('admin')->check() ? [
                    'id' => Auth::guard('admin')->user()->id,
                    'name' => Auth::guard('admin')->user()->username,
                    'email' => Auth::guard('admin')->user()->email,
                    'role' => 'admin',
                ] : null,
                'company' => Auth::guard('company')->check() ? [
                    'id' => Auth::guard('company')->user()->id,
                    'name' => Auth::guard('company')->user()->company_name,
                    'email' => Auth::guard('company')->user()->email,
                    'is_company' => 1,
                ] : null,
            ],
            'ziggy' => fn () => [
                ...(new Ziggy)->toArray(),
                'location' => $request->url(),
            ],
            'flash' => [
                'success' => fn() => $request->session()->get('success'),
                'error' => fn() => $request->session()->get('error'),
                'info' => fn() => $request->session()->get('info'),
                'warning' => fn() => $request->session()->get('warning'),
            ],
        ];
    }
}
