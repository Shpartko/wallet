<?php

namespace App\Http\Controllers;

use Log;
use Illuminate\Support\Facades\DB;
use App\Transaction;
use App\Operation;
use App\Wallet;
use App\Wallet_hist;
use App\Repositories\ExchangeRateRepository;
use App\Repositories\CurrencyDictRepository;

class JobController extends Controller
{
    public function run(Transaction $tran, $loging, $worker){
        if($loging){
            Log::info('Worker' . $worker . ' transaction ' . $tran->tran_id . ' job run');
        }
        $rate = ExchangeRateRepository::getRate($tran->currency_id, $tran->tran_date);
        $usd = floor(100 * $tran->amount / $rate->rate);//no round
        $tran_currency = CurrencyDictRepository::find($tran->currency_id);
        $tran_amount = floor($tran->amount * $tran_currency->fractional);
        
        //All in one transaction!
        DB::beginTransaction();
        
        if($tran->status != 'start'){
            if($loging){
                Log::warning('transaction' . $tran->tran_id . ' status != start');
            }
            return false;
        }
            
        $oper_id = $this->createOperation($tran, $usd, $tran_amount);        
        
        $wallet_to = $tran->wallet_to;
        $wallet_from=null;
        
        if($tran->operation == 'TRANSFER'){
            $wallet_from = $tran->wallet_from;
            //For from history minus balance
            $wallet_from_amount = -1 * $this->getWalletAmount($tran, $wallet_from, $usd, $tran_amount);
            $this->createWalletHist($tran, $wallet_from, $wallet_to, $oper_id, $wallet_from_amount);
            $this->incWalletBalance($wallet_from, $wallet_from_amount);
        }
        
        $wallet_to_amount = $this->getWalletAmount($tran, $wallet_to, $usd, $tran_amount);
        $this->createWalletHist($tran, $wallet_to, $wallet_from, $oper_id, $wallet_to_amount);
        $this->incWalletBalance($wallet_to, $wallet_to_amount);
        
        $tran->status='done';
        $tran->save();
        
        DB::commit();
    }
    
    private function createOperation(Transaction $tran, $usd, $tran_amount){        
        $oper = new Operation;
        $oper->currency_id = $tran->currency_id;
        $oper->operation = $tran->operation;
        $oper->oper_date = $tran->tran_date;
        $oper->oper_amount = $tran_amount;
        $oper->usd_amount = $usd;
        $oper->save();
                
        return $oper->oper_id;
    }
    
    private function createWalletHist(Transaction $tran, Wallet $wallet, $wallet_partner, $oper_id, $amount){        
        $wallet_hist = new Wallet_hist;
        $wallet_hist->wallet_id = $wallet->wallet_id;
        $wallet_hist->oper_id = $oper_id;
        $wallet_hist->hist_date = $tran->tran_date;
        $wallet_hist->type = ($amount>=0) ? 'IN' : 'OUT';
        $wallet_hist->amount = abs($amount);
        
        if(!is_null($wallet_partner)){
            $wallet_hist->wallet_partner_id = $wallet_partner->wallet_id;
            $wallet_hist->wallet_partner_name = $wallet_partner->client->name;
            
        }
        $wallet_hist->save();
    }
    
    private function getWalletAmount(Transaction $tran, Wallet $wallet, $usd, $tran_amount){
        if($tran->currency_id == $wallet->currency_id){
            return $tran_amount;
        }
        
        $rate = ExchangeRateRepository::getRate($wallet->currency_id, $tran->tran_date);
        $wallet_currency = CurrencyDictRepository::find($wallet->currency_id);
        return floor($usd * $rate->rate * $wallet_currency->fractional);
    }
    
    private function incWalletBalance(Wallet $wallet, $amount){
        DB::raw('update wallet set balance = + :amount where wallet_id=:wallet_id',
                ['amount' => $amount, 'wallet_id' => $wallet->wallet_id]);
    }
}
