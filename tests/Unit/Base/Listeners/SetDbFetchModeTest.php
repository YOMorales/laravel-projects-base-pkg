<?php

namespace Tests\Unit\Base\Listeners;

use Illuminate\Database\Events\StatementPrepared;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use PDOStatement;
use Tests\TestCase;

class SetDbFetchModeTest extends TestCase
{
    /**
     * Tests that the Listener SetDbFetchMode changes an option in created PDOStatements.
     *
     * @test
     * @covers App\Base\Listeners\SetDbFetchMode
     */
    public function listenerCallsSetFetchMode(): void
    {
        $connection = DB::connection('mysql');
        $mockedStatement = $this->spy(PDOStatement::class);

        // by firing this event, SetDbFetchMode listener should call setFetchMode on the mocked PDOStatement
        Event::dispatch(new StatementPrepared($connection, $mockedStatement));

        $mockedStatement->shouldHaveReceived('setFetchMode')->with($connection->getConfig('options.fetch_mode'));
    }
}
