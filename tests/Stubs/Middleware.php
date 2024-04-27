<?php

namespace Wistrix\Onboard\Tests\Stubs;

use Illuminate\Http\Request;
use Wistrix\Onboard\Concerns\Onboardable;
use Wistrix\Onboard\Middleware as BaseMiddleware;

class Middleware extends BaseMiddleware
{
    /**
     * Get the onboardable model.
     *
     * @param Request $request
     * @return Onboardable|null
     */
    protected function uses(Request $request): ? Onboardable
    {
        return $request->user();
    }
}
