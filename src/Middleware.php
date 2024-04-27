<?php

namespace Wistrix\Onboard;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Wistrix\Onboard\Concerns\Onboardable;

abstract class Middleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $model = $this->uses($request);

        if (is_null($model)) {
            return $next($request);
        }

        $onboarding = $model->onboarding();

        $redirectTo = match (true) {
            $this->isIgnoredRoute($request) => null,
            $onboarding->inProgress() => $onboarding->next()->route(),
            $this->isOnboardingRoute($request, $onboarding->routes()) => $this->defaultRoute(),
            default => null
        };

        if (! is_null($redirectTo) && ! $request->routeIs($redirectTo)) {
            return redirect()->route($redirectTo);
        }

        return $next($request);
    }

    /**
     * Get the onboardable model.
     *
     * @param Request $request
     * @return Onboardable|null
     */
    abstract protected function uses(Request $request): ? Onboardable;

    /**
     * Check whether the request route is an onboarding route.
     *
     * @param Request $request
     * @param array $routes
     * @return bool
     */
    protected function isOnboardingRoute(Request $request, array $routes): bool
    {
        return $request->routeIs(...$routes);
    }

    /**
     * Check whether the request route is an ignored route.
     *
     * @param Request $request
     * @return bool
     */
    protected function isIgnoredRoute(Request $request): bool
    {
        return $request->routeIs(...$this->ignoreRoutes());
    }

    /**
     * Get the ignore routes.
     *
     * @return array
     */
    protected function ignoreRoutes(): array
    {
        return [];
    }

    /**
     * Get the default route.
     *
     * @return string
     */
    protected function defaultRoute(): string
    {
        return 'home';
    }
}
