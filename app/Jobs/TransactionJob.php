<?php

namespace App\Jobs;

use Log;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Transaction;
use App\Http\Controllers\JobController;

class TransactionJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;
    protected $tran, $loging, $worker;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Transaction $tran, $loging, $worker)
    {
        $this->tran = $tran;
        $this->loging = $loging;
        $this->worker = $worker;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {   
        $controller = new JobController();
        $controller->run($this->tran, $this->loging, $this->worker);
    }
}
