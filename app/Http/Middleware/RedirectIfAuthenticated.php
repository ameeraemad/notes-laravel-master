<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
//        if (Auth::guard($guard)->check()) {
//            return redirect(RouteServiceProvider::HOME);
//        }

        if ($guard == 'admin'){
            if (Auth::guard('admin')->check()){
                $user = Auth::guard('admin')->user();
                if ($user->status == "Active"){
                    return redirect()->route('cms.admin.dashboard');
                }else{
                    // return redirect()->route('cms.author.blocked');
                }
            }
        }else if ($guard == 'student'){
            if (Auth::guard('student')->check()){
                $user = Auth::guard('student')->user();
                if ($user->status == "Active"){
                    return redirect()->route('cms.admin.dashboard');
//                    return redirect()->route('cms.student.dashboard');
                }else{
                    // return redirect()->route('cms.author.blocked');
                }
            }
        }
        return $next($request);
    }
}
