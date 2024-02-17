## Laravel Projects Base Package

Package that reunites common code for using in Laravel-based projects.

## Setup:

1) First, decide if you want a single app to be built in this codebase, or if you want
multiple related apps in it.
For example, a single app would mean one that fetches data from a particular third-party API.
And a multi app would mean one that fetches data from multiple third-party APIs that
are related (e.g. APIs for tracking order deliveries).

This base package provides two directory structures depending on the decision you made.
The `app-single` structure is the default Laravel structure and namespace.
The `app-multi` structure is one that groups common (shared) code in a
`App\Base` namespace so that other apps can use it. Also, there is a distinct directory
for each app; a dummy one is provided under `app-multi/MyApp`.

a) If you choose the `app-single` structure, delete the `app-multi` directory and rename
the retained directory to `app`. If you choose the `app-multi` structure, then do the
opposite.

b) You will also need to decide which of the following files to retain. Delete the
opposite ones and rename the retained ones to eliminate the suffix of 'single' or
'multi':

* `bootstrap/app-single.php` vs `bootstrap/app-multi.php`
* `config/app-single.php` vs `config/app-multi.php`
* `config/auth-single.php` vs `config/auth-multi.php`
* `config/sanctum-single.php` vs `config/sanctum-multi.php`
* `phpunit-single.xml` vs `phpunit-multi.xml`

c) Open the `composer.json` file and in the 'autoload' section, decide which of the
two files to keep:

* `app-single/Helpers/custom_functions.php`
* `app-multi/Base/Helpers/custom_functions.php`

2) For multi-app structures, change references to the dummy MyApp namespace (to reflect
the actual name of your app), as follows:

* The directory `app-multi/MyApp`
* The namespace `App\MyApp\Providers`
* In `config/app-multi.php`: App\MyApp\Providers\ServiceProvider::class
* The directory `tests/Integration/MyApp` and its namespace `Tests\Integration\MyApp`
* The directory `database/migrations/myapp`
* The directory `resources/views/myapp`
* The directory `routes/myapp`

3) Create an environment file for your local machine. Copy the `.env.example` and
rename to `.env`.
Then edit a few settings to include the name of your app.

* `APP_NAME`
* `APP_URL`
* `CACHE_PREFIX`
* `SESSION_COOKIE`
* `REDIS_PREFIX`
* `HORIZON_PREFIX`

Dont forget to generate an APP_KEY using the artisan command `key:generate`.

4) Run composer install and start coding. :)

5) Optional packages:

* Install the Horizon package if you are going to be scheduling jobs. A config file with necessary adjustments is already included in this repo. See: https://laravel.com/docs/9.x/horizon
* Install the Sentry package. A config file with necessary adjustments is already included in this repo. This will also require that you setup a project in Sentry.io: https://docs.sentry.io/platforms/php/guides/laravel/

6) Optional files:

* Custom Routes: If you are planning on creating your own custom routes, then place them under the the directory `routes/myapp/example.php` (of course, rename the directory to suit your app's name). Then, in `RouteServiceProvider`, load these routes like:
```
Route::middleware('web')->group(base_path('routes/myapp/example.php'));
```

* Custom Migrations: If you are planning on creating your own migrations, then place them under the the directory `database/migrations/myapp/` (of course, rename the directory to suit your app's name). Then, in the app ServiceProvider, load these migrations like:
```
$this->loadMigrationsFrom(__DIR__ . '/../../../database/migrations/myapp');
```
