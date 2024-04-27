<?php

use Wistrix\Onboard\Step;
use Wistrix\Onboard\Tests\Stubs\User;

beforeEach(function () {
    $this->user = new User;
});

it('correctly validates a complete step', function () {
    $step = new Step('step', fn(User $model) => $model->isTrue());

    $step->initiate($this->user);

    $this->assertEquals($step->complete(), true);
});

it('correctly validates a incomplete step', function () {
    $step = new Step('step', fn(User $model) => $model->isFalse());

    $step->initiate($this->user);

    $this->assertEquals($step->incomplete(), true);
});

it('returns the defined route', function () {
    $step = new Step('step', fn(User $model) => $model->isFalse());

    $step->initiate($this->user);

    $this->assertEquals($step->route(), 'step');
});
