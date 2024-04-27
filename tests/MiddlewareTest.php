<?php

use Illuminate\Support\Facades\Route;
use Wistrix\Onboard\Tests\Stubs\User;
use Wistrix\Onboard\Tests\Stubs\Middleware;

beforeEach(function () {
    $this->user = new User;

    Route::middleware(Middleware::class)->group(function () {
        Route::get('/', fn() => null)->name('home');
        Route::get('/onboarding/step1', fn() => null)->name('onboarding.step1');
        Route::get('/onboarding/step2', fn() => null)->name('onboarding.step2');;
    });
});

it('continues with a null model', function () {
    $response = $this->get(route('home'));

    $response->assertStatus(200);
});

it('continues with complete steps', function () {
    $this->be($this->user);

    $onboarding = $this->user->onboarding();

    $onboarding->register('onboarding.step1', fn() => true);
    $onboarding->register('onboarding.step2', fn() => true);

    $response = $this->get(route('home'));

    $response->assertStatus(200);
});

it('redirects with incomplete steps', function () {
    $this->be($this->user);

    $onboarding = $this->user->onboarding();

    $onboarding->register('onboarding.step1', fn() => true);
    $onboarding->register('onboarding.step2', fn() => false);

    $response = $this->get(route('home'));

    $response->assertStatus(302);
    $response->assertRedirectToRoute('onboarding.step2');
});

it('redirects with completed steps to default route', function () {
    $this->be($this->user);

    $onboarding = $this->user->onboarding();

    $onboarding->register('onboarding.step1', fn() => true);
    $onboarding->register('onboarding.step2', fn() => true);

    $response = $this->get(route('onboarding.step1'));

    $response->assertStatus(302);
    $response->assertRedirectToRoute('home');
});