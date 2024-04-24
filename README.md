# Laravel package to help track onboarding steps.

## Installation

You can install the package via composer:

```bash
composer require wistrix/laravel-onboard
```

## Usage

Add the `Wistrix\Onboard\Concerns\Onboardable` interface and `Wistrix\Onboard\Concerns\Onboard` trait to your desired models.

```php
...
use Wistrix\Onboard\Concerns\Onboardable;
use Wistrix\Onboard\Concerns\Onboard;
...

class User extends Model implements Onboardable
{
    use Onboard;
    ...
```

### Middleware

Create a new middleware and extend the abstract `Wistrix\Onboard\Middleware` class.

```php
use Illuminate\Http\Request;
use Wistrix\Onboard\Concerns\Onboardable;
use Wistrix\Onboard\Middleware;

class UserOnboarding extends Middleware
{
    /**
     * Get the onboardable model.
     *
     * @param Request $request
     * @return Onboardable|null
     */
    protected function uses(Request $request): ? Onboardable
    {
        return $request->user();
    }
    
    /**
     * Register the onboarding steps.
     *
     * @param Manager $manager
     * @return void
     */
    protected function register(Manager $manager): void
    {
        $manager->register(
            route: 'onboarding.name',
            validate: fn (User $model) => ! empty($model->name)
        );
        
        $manager->register(
            route: 'onboarding.username',
            validate: fn (User $model) => ! empty($model->username)
        );
    }
}
```

#### Default Route

By default, the `Wistrix\Onboard\Middleware` class defines the default route `home` to redirect users too, if the onboarding is complete and a step route is accessed. This can be customised by adding the `DEFAULT_ROUTE` const to your middleware.

```php
/**
 * The default redirect route.
 */
CONST string DEFAULT_ROUTE = 'home';
```

#### Ignore Routes

You can define routes to be ignored by adding the `ignoreRoutes` method. This is useful if you have registered your middleware via the `bootstrap/app.php` configuration and want to ignore the `logout` route for example.

```php
/**
 * Get the ignore routes.
 *
 * @return array
 */
protected function ignoreRoutes(): array
{
    return ['logout'];
}
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information.

## Security

If you discover a security vulnerability, please raise a GitHub [issue](https://github.com/wistrix/laravel-onboard/issues).

## License

The MIT License (MIT).

Please see [License File](LICENSE.md) for more information.
