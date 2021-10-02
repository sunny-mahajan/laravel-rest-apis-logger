<?php

namespace TF\Console\Commands;

use Illuminate\Console\Command;
use TF\Contracts\RestLoggerInterface;

class ClearRestLogger extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'restlogs:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Flush All Records of RestLogger';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(RestLoggerInterface $apiLogger)
    {
        $apiLogger->deleteLogs();

        $this->info("All records are deleted");
    }
}
