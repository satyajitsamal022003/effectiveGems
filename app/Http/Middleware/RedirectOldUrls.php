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
        // dd($request->path());
        $fullUrl = $request->getSchemeAndHttpHost() . $request->getRequestUri();
        $redirect = Redirect::where('old_url', $fullUrl)->where('status',1)->first();

        // dd($fullUrl);


        if ($redirect) {
            return redirect($redirect->new_url, 301);
        }

        return $next($request);
    }
}
