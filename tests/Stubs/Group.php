<?php

namespace Wistrix\Onboard\Tests\Stubs;

use Wistrix\Onboard\Concerns\Onboard;
use Wistrix\Onboard\Concerns\Onboardable;

class Group implements Onboardable
{
    use Onboard;

    public function isTrue(): true
    {
        return true;
    }

    public function isFalse(): false
    {
        return false;
    }
}
