<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Queue;
use App\Jobs\MainTransactionJob;

class TransactionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transaction:run {--sleep=10} {--pack_cnt=500} {--worker_cnt=5} {--loging=false}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run MainTransactionJob';

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
    {//Only push job
        $job = new MainTransactionJob(
                $this->option('sleep')
                , $this->option('pack_cnt')
                , $this->option('worker_cnt')
                , $this->option('loging') != 'false'
                );
        Queue::pushOn('MainTransaction', $job);
        if($this->option('loging')){
            $this->info('MainTransactionJob added');
        }
    }
}
