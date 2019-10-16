<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TestSupervisor extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:supervisor';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'test supervisor';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        while (1) {
            $this->info('waiting...');
            myLog('test_supervisor', ['waiting...']);
            sleep(5);
        }
    }
}
