<?php

use Wistrix\Onboard\Flows;
use Wistrix\Onboard\Manager;
use Wistrix\Onboard\Step;
use Wistrix\Onboard\Tests\Stubs\User;

beforeEach(function () {
    $this->user = new User;
    $this->flows = new Flows;
});

it('can register steps correctly', function () {
    $onboarding = new Manager($this->user, $this->flows);

    $onboarding->register('step1', fn(User $model) => $model->isTrue());
    $onboarding->register('step2', fn(User $model) => $model->isTrue());

    $this->assertEquals(2, $onboarding->steps()->count());

    $step1 = $onboarding->steps()->first();
    $step2 = $onboarding->steps()->last();

    /**
     * @var Step $step1
     * @var Step $step2
     */
    $this->assertEquals($step1->route(), 'step1');
    $this->assertEquals($step2->route(), 'step2');
});

it('is complete when all steps are complete', function () {
    $onboarding = new Manager($this->user, $this->flows);

    $onboarding->register('step1', fn(User $model) => $model->isTrue());
    $onboarding->register('step2', fn(User $model) => $model->isTrue());

    expect($onboarding->isComplete())->toBeTrue()
        ->and($onboarding->inProgress())->toBeFalse();
});

it('is in progress when incomplete steps exist', function () {
    $onboarding = new Manager($this->user, $this->flows);

    $onboarding->register('step1', fn(User $model) => $model->isTrue());
    $onboarding->register('step2', fn(User $model) => $model->isFalse());

    expect($onboarding->inProgress())->toBeTrue()
        ->and($onboarding->isComplete())->toBeFalse();
});

it('returns the correct next incomplete step', function () {
    $onboarding = new Manager($this->user, $this->flows);

    $onboarding->register('step1', fn(User $model) => $model->isTrue());
    $onboarding->register('step2', fn(User $model) => $model->isFalse());

    $next = $onboarding->next();

    expect($next)->not()->toBeNull()
        ->and($next->route())->toBe('step2')
        ->and($next->incomplete())->toBeTrue();
});

it('passes the correct model instance into the validate callback', function () {
    $user = $this->mock(User::class);
    $user->shouldReceive('isTrue')->once();

    $onboarding = new Manager($user, $this->flows);

    $onboarding->register('step1', fn(User $model) => $model->isTrue());

    expect($onboarding->isComplete())->toBeTrue();
});

it('will only run validate callbacks once', function () {
    $onboarding = new Manager($this->user, $this->flows);

    $called = 0;

    $onboarding->register('step1', function(User $model) use (&$called) {
        $called++;
        return $model->isTrue();
    });

    $onboarding->isComplete();

    expect($called)->toBe(1);

    $onboarding->isComplete();

    expect($called)->toBe(1);
});
