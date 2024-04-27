<?php

namespace Wistrix\Onboard\Facades;

use Closure;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Facade;
use Wistrix\Onboard\Concerns\Onboardable;
use Wistrix\Onboard\Flows;
use Wistrix\Onboard\Step;

/**
 * @method static Step register(string $model, string $route, Closure $validate)
 * @method static Collection steps(Onboardable $model)
 */
class Onboard extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return Flows::class;
    }
}