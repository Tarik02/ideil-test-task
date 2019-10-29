<?php

namespace App\Http\Controllers\Auth;

trait RedirectToRequestFieldTrait
{
    public function redirectTo()
    {
        if (\Request::has('redirect_to')) {
            return \Request::get('redirect_to');
        } else {
            return route('index');
        }
    }
}
