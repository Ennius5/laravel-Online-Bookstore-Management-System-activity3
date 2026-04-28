<?php

namespace App\Resolvers;

use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Contracts\Resolver;
use Illuminate\Support\Facades\Auth;

class UserResolver implements Resolver
{
    public static function resolve(Auditable $auditable): ?Auditable
    {
        // Return the currently authenticated user (or null if guest)
        return Auth::user();
    }
}
