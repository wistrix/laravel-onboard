<?php

use Illuminate\Support\Facades\Route;
use Wistrix\Onboard\Tests\Stubs\User;
use Wistrix\Onboard\Tests\Stubs\Middleware;

beforeEach(function () {
    $this->user = new User;

    Route::middleware(Middleware::class)->group(function () {
        Route::get('/', fn() => null)->name('home');
        Route::get('/step1', fn() => null)->name('step1');
        Route::get('/step2', fn() => null)->name('step2');;
    });
});

it('continues with a null model', function () {
    $response = $this->get(route('home'));

    $response->assertStatus(200);
});

it('continues with complete steps', function () {
    $this->be($this->user);

    $onboarding = $this->user->onboarding();

    $onboarding->register('step1', fn(User $model) => $model->isTrue());
    $onboarding->register('step2', fn(User $model) => $model->isTrue());

    $response = $this->get(route('home'));

    $response->assertStatus(200);
});

it('redirects with incomplete steps', function () {
    $this->be($this->user);

    $onboarding = $this->user->onboarding();

    $onboarding->register('step1', fn(User $model) => $model->isTrue());
    $onboarding->register('step2', fn(User $model) => $model->isFalse());

    $response = $this->get(route('home'));

    $response->assertStatus(302);
    $response->assertRedirectToRoute('step2');
});

it('redirects with completed steps to default route', function () {
    $this->be($this->user);

    $onboarding = $this->user->onboarding();

    $onboarding->register('step1', fn(User $model) => $model->isTrue());
    $onboarding->register('step2', fn(User $model) => $model->isTrue());

    $response = $this->get(route('step1'));

    $response->assertStatus(302);
    $response->assertRedirectToRoute('home');
});