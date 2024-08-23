<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendActivityReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send-activity-report';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send activity report';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        // Log::info('send activity report command ran info');

        $this->info('send activity report command ran');

    }
}
