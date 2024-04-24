<?php

namespace Wistrix\Onboard\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Wistrix\Onboard\ServiceProvider;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            ServiceProvider::class,
        ];
    }
}
