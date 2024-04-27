<?php

namespace Wistrix\Onboard;

use Closure;
use Wistrix\Onboard\Concerns\Onboardable;

class Step
{
    /**
     * The onboardable model.
     *
     * @var Onboardable
     */
    protected Onboardable $model;

    /**
     * Create a new onboarding step instance.
     *
     * @param string $route
     * @param Closure $validate
     */
    public function __construct(
        protected string $route,
        protected Closure $validate
    ) {}

    /**
     * Set the given onboardable model.
     *
     * @param Onboardable $model
     * @return Step
     */
    public function initiate(Onboardable $model): Step
    {
        $this->model = $model;

        return $this;
    }

    /**
     * Get whether the step is incomplete.
     *
     * @return bool
     */
    public function incomplete(): bool
    {
        return ! $this->complete();
    }

    /**
     * Get whether the step is complete.
     *
     * @return bool
     */
    public function complete(): bool
    {
        return once(fn () => app()->call($this->validate, ['model' => $this->model]));
    }

    /**
     * Get the step route.
     *
     * @return string
     */
    public function route(): string
    {
        return $this->route;
    }
}
