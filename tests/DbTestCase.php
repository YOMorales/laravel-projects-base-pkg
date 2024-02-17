<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabaseState;

class DbTestCase extends TestCase
{
    use DatabaseMigrations;
    use DatabaseTransactions;

    /**
     * Overrides Laravel's DatabaseMigrations::runDatabaseMigrations() to improve the use of migration files in tests.
     *
     * The original runDatabaseMigrations() method runs on *every test file* that would use the RefreshDatabase trait,
     * and *reruns the entire set of migration files* by way of `$this->artisan('migrate:fresh')` and
     * `$this->artisan('migrate:rollback')`. This level of slowness can be avoided.
     *
     * So instead, we check the state of the test db migrations (RefreshDatabaseState). If migrations have not been
     * completed yet, then we apply any migration files that need to be applied. Then we set such state to true so
     * that migrations are not checked again on subsequent tests.
     *
     * Note: the DatabaseTransactions trait being used in this class guarantees that data written during tests is
     * done in a transaction and thus rolled back when the tests end.
     *
     * @return void
     */
    public function runDatabaseMigrations(): void
    {
        if (! RefreshDatabaseState::$migrated) {
            $this->artisan('migrate');
        }

        RefreshDatabaseState::$migrated = true;

        // as per overridden method
        // @phpstan-ignore-next-line (overriden method IS like this)
        $this->app[Kernel::class]->setArtisan(null);
    }
}
