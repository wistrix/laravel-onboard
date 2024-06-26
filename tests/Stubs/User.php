<?php

namespace Wistrix\Onboard\Tests\Stubs;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Wistrix\Onboard\Concerns\Onboard;
use Wistrix\Onboard\Concerns\Onboardable;

class User extends Authenticatable implements Onboardable
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
