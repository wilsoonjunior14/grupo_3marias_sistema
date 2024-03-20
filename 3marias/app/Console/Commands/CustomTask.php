<?php

namespace App\Console\Commands;

use App\Http\Controllers\ObservabilityController;
use Illuminate\Console\Command;

class CustomTask extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'custom:task';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to collect server data each fifteen minutes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $controller = new ObservabilityController();
        $controller->storeServerMetrics();
    }
}
