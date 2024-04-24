<?php

namespace Wistrix\Onboard\Concerns;

use Wistrix\Onboard\Manager;

interface Onboardable
{
    /**
     * Get the onboarding manager instance.
     *
     * @return Manager
     */
    public function onboarding(): Manager;
}
