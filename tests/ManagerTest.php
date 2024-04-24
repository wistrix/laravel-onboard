<?php

use Wistrix\Onboard\Manager;
use Wistrix\Onboard\Step;
use Wistrix\Onboard\Tests\Stubs\User;

beforeEach(function () {
    $this->user = new User;
});

it('can register steps correctly', function () {
    $onboarding = new Manager($this->user);

    $onboarding->register('step1', fn() => true);
    $onboarding->register('step2', fn() => true);

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
    $onboarding = new Manager($this->user);

    $onboarding->register('step1', fn() => true);
    $onboarding->register('step2', fn() => true);
    $onboarding->register('step3', fn() => true);

    expect($onboarding->isComplete())->toBeTrue()
        ->and($onboarding->inProgress())->toBeFalse();
});

it('is in progress when incomplete steps exist', function () {
    $onboarding = new Manager($this->user);

    $onboarding->register('step1', fn() => true);
    $onboarding->register('step2', fn() => false);
    $onboarding->register('step3', fn() => false);

    expect($onboarding->inProgress())->toBeTrue()
        ->and($onboarding->isComplete())->toBeFalse();
});

it('returns the correct next incomplete step', function () {
    $onboarding = new Manager($this->user);

    $onboarding->register('step1', fn() => true);
    $onboarding->register('step2', fn() => false);
    $onboarding->register('step3', fn() => false);

    $next = $onboarding->next();

    expect($next)->not()->toBeNull()
        ->and($next->route())->toBe('step2')
        ->and($next->incomplete())->toBeTrue();
});

it('passes the correct model instance into the validate callback', function () {
    $user = $this->mock(User::class);
    $user->shouldReceive('foo')->once();

    $onboarding = new Manager($user);

    $onboarding->register('step1', function($model) {
        $model->foo();
        return true;
    });

    expect($onboarding->isComplete())->toBeTrue();
});

it('will only run validate callbacks once', function () {
    $called = 0;
    $onboarding = new Manager($this->user);

    $onboarding->register('step1', function() use (&$called) {
        $called++;
        return true;
    });

    $onboarding->register('step2', fn() => false);

    $onboarding->isComplete();

    expect($called)->toBe(1);

    $onboarding->isComplete();

    expect($called)->toBe(1);
});
