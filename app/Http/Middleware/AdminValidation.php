<?php

namespace App\Http\Middleware;

use App\Models\User;
use Auth;
use Closure;

class AdminValidation {
	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next) {
		if (Auth::check()) {
			if (User::where('is_admin', '1')->where('id', Auth::id())->first()) {
				return $next($request);
			} else {
				return redirect()->route('index');
			}
		} else {
			return redirect()->route('login'); // to get the current url with request parameters and redirect on login
		}
	}
}
