<?php

namespace Wistrix\Onboard\Tests\Stubs;

use Illuminate\Http\Request;
use Wistrix\Onboard\Concerns\Onboardable;
use Wistrix\Onboard\Manager;
use Wistrix\Onboard\Middleware;

class UserOnboarded extends Middleware
{
    /**
     * The default redirect route.
     */
    CONST string DEFAULT_ROUTE = 'home';

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

    /**
     * Register the onboarding steps.
     *
     * @param Manager $manager
     * @return void
     */
    protected function register(Manager $manager): void
    {
        //
    }
}
