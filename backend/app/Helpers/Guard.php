<?php

if (! function_exists('getActiveGuard')) {
    function getActiveGuard()
    {
        foreach(array_keys(config('auth.guards')) as $guard){
            if(auth()->guard($guard)->check()) return $guard;
        }
        return null;
    }
}

if (! function_exists('getUser')) {
    function getUser()
    {
        return auth(getActiveGuard())->user();
    }
}
