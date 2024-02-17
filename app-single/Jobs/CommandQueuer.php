<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Console\Command;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;

/**
 * Wrapper for Artisan commands that leverage the capabilities of queued jobs.
 *
 * Laravel already can do `$schedule->command()`, but this lacks the handy methods provided
 * by InteractsWithQueue trait.
 * On the other hand, Laravel can do `Artisan::queue()`, but this lacks the handy methods of
 * the ManagesFrequencies trait (e.g. dailyAt(), etc.)
 *
 * So with this custom class, we wrap commands in a traditional job so it can have all the
 * mentioned benefits.
 */
class CommandQueuer implements ShouldBeUnique, ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $commandClassName;

    public array $commandParameters;

    /**
     * The number of seconds after which the job's unique lock will be released.
     *
     * @var int
     */
    public $uniqueFor = 7200;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 590;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     *
     * @var int
     */
    public $backoff = 300;

    /**
     * The maximum number of unhandled exceptions to allow before failing.
     *
     * @var int
     * @see https://laravel.com/docs/10.x/queues#max-exceptions
     */
    public $maxExceptions = 3;

    /**
     * Create a new job instance.
     *
     * @param array $commandParameters
     * @return void
     */
    public function __construct(string $commandClassName, array $commandParameters = [], ?array $jobConfig = null)
    {
        $this->commandClassName = $commandClassName;
        $this->commandParameters = $commandParameters;

        // either use the passed configs or the existing default values
        $this->uniqueFor = $jobConfig['uniqueFor'] ?? $this->uniqueFor;
        $this->timeout = $jobConfig['timeout'] ?? $this->timeout;
        $this->tries = $jobConfig['tries'] ?? $this->tries;
        $this->backoff = $jobConfig['backoff'] ?? $this->backoff;
        $this->maxExceptions = $jobConfig['maxExceptions'] ?? $this->maxExceptions;

        $this->onConnection($jobConfig['connection'] ?? config('queue.default'));
        $this->onQueue($jobConfig['queue'] ?? config('queue.connections.redis.queue'));
    }

    /**
     * The unique ID of the job.
     *
     * Consists of the basename of the passed Command class plus
     * an md5 hash of every passed parameter.
     *
     * Example: _MyCommand_46f2274a2b1bb1bc0a2f645cebdc63ab
     *
     * @return string
     */
    public function uniqueId(): string
    {
        return sprintf(
            '_%s_%s',
            basename(str_replace('\\', '/', $this->commandClassName)),
            md5(implode(',', $this->commandParameters))
        );
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        $response = Artisan::call($this->commandClassName, $this->commandParameters);

        if ($response !== Command::SUCCESS) {
            report("Call to '{$this->commandClassName}' resulted in a non-successful exit code.");
        }
    }

    /**
    * Calculate the number of seconds to wait before retrying the job.
    */
    public function backoff(): int
    {
        return $this->backoff;
    }
}
