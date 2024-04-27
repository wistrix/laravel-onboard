<?php

namespace Wistrix\Onboard;

use Closure;
use Illuminate\Support\Collection;
use Wistrix\Onboard\Concerns\Onboardable;

class Flows
{
    /**
     * The registered flows.
     *
     * @var array
     */
    protected array $flows = [];

    /**
     * Register a new onboarding step.
     *
     * @param string $model
     * @param string $route
     * @param Closure $validate
     * @return Step
     */
    public function register(string $model, string $route, Closure $validate): Step
    {
        return $this->flows[$model][] = new Step($route, $validate);
    }

    /**
     * Get the onboarding steps.
     *
     * @param Onboardable $model
     * @return Collection
     */
    public function steps(Onboardable $model): Collection
    {
        $steps = $this->getStepsArray($model);

        return (new Collection($steps))->map(
            fn (Step $step) => $step->initiate($model)
        );
    }

    /**
     * Get the given model steps.
     *
     * @param Onboardable $model
     * @return array
     */
    protected function getStepsArray(Onboardable $model): array
    {
        $key = get_class($model);

        return $this->flows[$key] ?? [];
    }
}
