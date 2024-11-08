<?php
namespace App\Http\Middleware;

use App\Models\Redirect;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectOldUrls
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        // Get only the path portion of the URL
        $path = $request->path();
        // dd($path);
        $redirect = Redirect::where('old_url', $path)->where('status', 1)->first();

        // If a matching redirect is found, redirect to the new URL
        if ($redirect) {
            return redirect($redirect->new_url, 301);
        }

        // If no redirect is found, proceed with the next middleware
        return $next($request);
    }
}
