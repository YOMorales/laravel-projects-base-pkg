<?php

namespace App\Listeners;

use Illuminate\Database\Events\StatementPrepared;
use PDO;

/**
 * Laravel currently defaults to fetching database data in objects. They eliminated a config in
 * database.php that allowed us to specify if we instead wanted data in arrays.
 * So this listener brings that functionality.
 *
 * This listener will change the setFetchMode of an SQL statement so that it uses the configured
 * options.fetch_mode or else it defaults to objects as usual. So this requires that you set
 * options.fetch_mode in database.php for each database config.
 *
 * The change is done on the fly because that's the only way we can 'hack' Laravel to do that.
 * When Laravel prepares an SQL statement, it fires an event of StatementPrepared, and here
 * we listen to that.
 */
class SetDbFetchMode
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @return void
     */
    public function handle(StatementPrepared $event): void
    {
        $event->statement->setFetchMode(
            $event->connection->getConfig('options.fetch_mode') ?? PDO::FETCH_OBJ
        );
    }
}
