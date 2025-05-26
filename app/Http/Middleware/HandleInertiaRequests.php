<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        // Add a key value to share prop to all pages
        return [
            ...parent::share($request),
            // Synchronously
            // 'auth.user' => 'Stephen' ?? null, // can be used if user is logged in
            // Lazily... 
            // Passing auth.user props to inertia to be accessible to all pages
            // auth.user will have data once user is logged in with cookie laravel_session
            'auth.user' => fn () => $request->user() ? $request->user()->only('id','name', 'email', 'image', 'created_at_formatted') : null
        ];
    }
}
