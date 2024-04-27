<?php

use Wistrix\Onboard\Flows;
use Wistrix\Onboard\Step;
use Wistrix\Onboard\Tests\Stubs\Group;
use Wistrix\Onboard\Tests\Stubs\User;

beforeEach(function () {
    $this->user = new User;
    $this->flows = new Flows;
});

it('can register model steps correctly', function () {
    $this->flows->register(User::class, 'step1', fn(User $model) => $model->isTrue());
    $this->flows->register(Group::class, 'step1', fn(Group $model) => $model->isTrue());
    $this->flows->register(Group::class, 'step2', fn(Group $model) => $model->isTrue());

    $userSteps = $this->flows->steps(new User);
    $groupSteps = $this->flows->steps(new Group);

    $this->assertEquals(1, $userSteps->count());
    $this->assertEquals(2, $groupSteps->count());
});

it('returns the correct model steps', function () {
    $this->flows->register(User::class, 'user.step1', fn(User $model) => $model->isTrue());
    $this->flows->register(Group::class, 'group.step1', fn(Group $model) => $model->isTrue());

    $userStep = $this->flows->steps(new User)->first();
    $groupStep = $this->flows->steps(new Group)->first();

    /**
     * @var Step $userStep
     * @var Step $groupStep
     */
    $this->assertEquals($userStep->route(), 'user.step1');
    $this->assertEquals($groupStep->route(), 'group.step1');
});
