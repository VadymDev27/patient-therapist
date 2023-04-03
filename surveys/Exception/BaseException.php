<?php

namespace Surveys\Exception;

use Exception;
use Illuminate\Http\Request;

class BaseException extends Exception
{
    public function render(Request $request)
    {
        return response()->view('error', ['className' => get_class($this) ], 403);
    }
}
