<?php

namespace Wistrix\Onboard\Concerns;

use Wistrix\Onboard\Manager;
use Illuminate\Support\Facades\App;

trait Onboard
{
    /**
     * Get the onboarding manager instance.
     *
     * @return Manager
     */
    public function onboarding(): Manager
    {
        return once(fn() => App::make(Manager::class, [
            'model' => $this
        ]));
    }
}
