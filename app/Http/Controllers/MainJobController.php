<?php

namespace App\Http\Controllers;

use Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use App\Last_transaction;
use App\Transaction;
use App\Jobs\TransactionJob;
use App\Repositories\JobRepository;

class MainJobController extends Controller
{
    protected $pack_cnt, $worker_cnt, $loging;
    
    public function start($pack_cnt, $worker_cnt, $loging){
        $this->pack_cnt = $pack_cnt;
        $this->worker_cnt = $worker_cnt;
        $this->loging = $loging;
        
        $tran_id = Last_transaction::get()->max('tran_id');
        
        if($this->loging){
            Log::info('Main job run. Transactions: ' . $tran_id . ' - ' . ($tran_id + $this->pack_cnt));
        }
        
        if(JobRepository::getJobUncommited('tran%')->count() > 0){
            if($this->loging){
                Log::info('Workers runned');
            }
            return;
        }
        
        DB::beginTransaction();
        $trans = Transaction::whereBetween('tran_id', [$tran_id, $tran_id + $this->pack_cnt ]);
        if($trans->get()->count()==0){
            return true;//Nothing prepare
        }
        
        $trans->whereNull('status')
                ->orWhere('status', 'start');//When process killed we starting agains
        $trans->update(['status'=>'start']);
        DB::commit();
        
        $transactions = $trans->orderBy('tran_id', 'desc')->get();
        
        if($transactions->count()==0){
            $this->finish();
            return false;
        }
        
        while(!$transactions->isEmpty()){
            for($i=1; $i<$this->worker_cnt + 1 && !$transactions->isEmpty(); $i++){
                $cur_tran = $transactions->pop();
                $job = new TransactionJob($cur_tran, $this->loging, $i);
                Queue::pushOn('tran' . $i, $job);
                if($this->loging){
                    Log::info('Worker ' . $i .' tran pushed ' . $cur_tran->tran_id);
                }
            }
        }
        
        return true;
    }
    
    public function finish(){        
        $tran_id = Last_transaction::get()->max('tran_id');
        
        DB::beginTransaction();
        $tran_cnt = Transaction::whereBetween('tran_id', [$tran_id, $tran_id + $this->pack_cnt])
                ->where('status', '!=', 'done')->count();
        
        if($this->loging){
            Log::info('Main job finishing. Transactions: ' . $tran_id . ' - ' . ($tran_id + $this->pack_cnt)
                . '. Transactions not done: ' . $tran_cnt);
        }
        
        if($tran_cnt == 0){
            Transaction::whereBetween('tran_id', [$tran_id, $tran_id + $this->pack_cnt])->update(['status'=>'ended']);
            Last_transaction::query()->update(['tran_id' => $tran_id  + $this->pack_cnt ]);
            
            if($this->loging){
                Log::info('Main job finished. Transaction: ' . $tran_id . ' - ' . ($tran_id + $this->pack_cnt));
            }
        }
        DB::commit();       
    }
}
