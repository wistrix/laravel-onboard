<?php

namespace Wistrix\Onboard;

use Wistrix\Onboard\Concerns\Onboardable;
use Closure;
use Illuminate\Support\Collection;

class Manager
{
    /**
     * The registered steps.
     *
     * @var Collection
     */
    protected Collection $steps;

    /**
     * Create a new onboarding manager instance.
     *
     * @param Onboardable $model
     * @param Collection $steps
     */
    public function __construct(protected Onboardable $model)
    {
        $this->steps = new Collection;
    }

    /**
     * Register a new onboarding step.
     *
     * @param string $route
     * @param Closure $validate
     * @return Manager
     */
    public function register(string $route, Closure $validate): Manager
    {
        $this->steps->push(new Step($this->model, $route, $validate));

        return $this;
    }

    /**
     * Get the onboarding steps.
     *
     * @return Collection
     */
    public function steps(): Collection
    {
        return $this->steps;
    }

    /**
     * Get whether the onboarding is in progress.
     *
     * @return bool
     */
    public function inProgress(): bool
    {
        return ! $this->isComplete();
    }

    /**
     * Get whether the onboarding is complete.
     *
     * @return bool
     */
    public function isComplete(): bool
    {
        return $this->steps
            ->filter(fn (Step $step) => $step->incomplete())
            ->isEmpty();
    }

    /**
     * Get the onboarding step routes.
     *
     * @return array
     */
    public function routes(): array
    {
        return $this->steps
            ->map(fn (Step $step) => $step->route())
            ->toArray();
    }

    /**
     * Get the next incomplete step.
     *
     * @return Step|null
     */
    public function next(): ? Step
    {
        return $this->steps->first(fn (Step $step) => $step->incomplete());
    }
}
