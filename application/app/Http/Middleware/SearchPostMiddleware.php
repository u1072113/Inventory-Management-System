<?php namespace App\Http\Middleware;

use Closure;
use Input;
class SearchPostMiddleware {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		if(Input::has('search')){
			$data = trim(Input::get('search'));
			Input::replace(array('search'=>$data));

		}
		return $next($request);
	}

}
