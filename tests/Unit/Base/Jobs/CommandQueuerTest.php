<?php

namespace Tests\Unit\Base\Jobs;

use App\Base\Jobs\CommandQueuer;
use Illuminate\Console\Command;
use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Foundation\Console\EnvironmentCommand;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class CommandQueuerTest extends TestCase
{
    /**
     * @test
     */
    public function jobIsInstantiatedWithProperParams(): void
    {
        $commandClassName = AboutCommand::class;
        $commandParameters = ['--json'];

        $job = new CommandQueuer($commandClassName, $commandParameters, ['connection' => 'test', 'queue' => 'queue']);

        $this->assertEquals($commandClassName, $job->commandClassName);
        $this->assertEquals($commandParameters, $job->commandParameters);
        $this->assertEquals('test', $job->connection);
        $this->assertEquals('queue', $job->queue);
    }

    /**
     * @test
     */
    public function jobIsDispatchedAndCallsThePassedCommand(): void
    {
        Queue::fake();
        Artisan::shouldReceive('call')
            ->with(AboutCommand::class, ['--json'])
            ->once()
            ->andReturn(Command::SUCCESS);

        CommandQueuer::dispatch(AboutCommand::class, ['--json']);

        Queue::assertPushed(function (CommandQueuer $job) {
            // the job should use the default connection and queue as configured in config/queue.php
            $this->assertEquals('sync', $job->connection);
            $this->assertEquals('myapp_test', $job->queue);

            // now runs the job so it calls the Artisan command as expected
            $job->handle();
            return true;
        });
    }

    /**
     * This test is for the ShouldBeUnique interface, which guarantees that only
     * *one* job per command is in the queue, avoiding duplicates.
     *
     * @test
     */
    public function onlyOneJobPerCommandIsQueued(): void
    {
        $this->withoutJobs();

        // even when we dispatch three jobs, only two should be present in the queue
        // (the other duplicate AboutCommand job is not queued)
        CommandQueuer::dispatch(EnvironmentCommand::class, ['--verbose']);
        CommandQueuer::dispatch(AboutCommand::class, ['--json']);
        CommandQueuer::dispatch(AboutCommand::class, ['--json']);

        $this->assertCount(2, $this->dispatchedJobs);
    }
}
