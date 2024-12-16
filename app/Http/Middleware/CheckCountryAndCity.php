<?php

namespace App\Http\Middleware;

use App\Models\City;
use App\Models\Country;
use Closure;
use Illuminate\Http\Request;

class CheckCountryAndCity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if(is_null(request()->header("country"))) {
            return (new \App\Support\API)->isError(__("Please select the country"))->setErrors([
                "country"  => __("Please select the country")
            ])->build();
        }
        $country = Country::find(request()->header("country"));
        if(is_null($country)) {
            return (new \App\Support\API)->isError(__("Please select the country"))->setErrors([
                "country"  => __("Please select the country")
            ])->build();
        }
        if(is_null(request()->header("city"))) {
            return (new \App\Support\API)->isError(__("Please select the city"))->setErrors([
                "city"  => __("Please select the city")
            ])->build();
        }
        $city = City::find(request()->header("city"));
        if(is_null($city)) {
            return (new \App\Support\API)->isError(__("Please select the city"))->setErrors([
                "city"  => __("Please select the city")
            ])->build();
        }
        if($country->id != $city->country_id) {
            return (new \App\Support\API)->isError(__("This city not support for this country"))->setErrors([
                "city"  => __("This city not support for this country")
            ])->build();
        }
        return $next($request);
    }
}
