<?php

namespace App\Http\Middleware;

use Illuminate\Routing\Middleware\ValidateSignature as Middleware;

class ValidateSignature extends Middleware
{
    /**
     * Query string parameters that should be ignored during signature validation.
     *
     * @var array<int, string>
     */
    protected $except = [
        //
    ];
}
