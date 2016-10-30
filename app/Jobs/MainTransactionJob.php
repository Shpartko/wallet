<?php

namespace App\Jobs;

use Log;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Queue;
use App\Http\Controllers\MainJobController;

class MainTransactionJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;
    protected $sleep, $pack_cnt, $worker_cnt, $loging;


/**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($sleep, $pack_cnt, $worker_cnt, $loging)
    {
        $this->sleep = $sleep;
        $this->pack_cnt = $pack_cnt;
        $this->worker_cnt = $worker_cnt;
        $this->loging = $loging;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if($this->loging){
            Log::info('MainTransactionJob run. sleep ' . $this->sleep
                    . ', pack_cnt ' . $this->pack_cnt
                    . ', worker_cnt ' . $this->worker_cnt);
        }
        
        $controller = new MainJobController;
        $res = $controller->start($this->pack_cnt, $this->worker_cnt, $this->loging);
        if($res){
            sleep($this->sleep);
        }
        
        //add himself
        $job = new MainTransactionJob(
                $this->sleep
                , $this->pack_cnt
                , $this->worker_cnt
                , $this->loging
                );
        Queue::pushOn('MainTransaction', $job);
    
    }
}
