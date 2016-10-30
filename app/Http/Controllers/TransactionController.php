<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Transaction;
use App\Repositories\ClientRepository;
use App\Repositories\WalletRepository;
use App\Repositories\ExchangeRateRepository;

class TransactionController extends Controller
{
    /*
     * Run refill wallet balance transaction
     */
    public function clientTransaction(Request $request, $name){
        $response = $this->validateRequest($request, [
            'amount' => 'required|numeric',
        ]);
        if ($response) {
            return $response;
        }
        
        $wallet=ClientRepository::getWalletUncommited($name);
        return $this->createWalletTransaction($wallet, $request->amount);
    }
    
    /*
     * Run refill wallet balance transaction
     */
    public function walletTransaction(Request $request, $wallet_id){
        $response = $this->validateRequest($request, [
            'amount' => 'required|numeric',
        ]);
        if ($response) {
            return $response;
        }
        
        $wallet = WalletRepository::getWalletUncommited($wallet_id);
        return $this->createWalletTransaction($wallet, $request->amount);
    }
    
    /*
     * Validate request and amount
     */
    private function validateRequest($request, $parameters){
        $validator = Validator::make($request->all(), $parameters);

        if ($validator->fails()) {
            return response($validator->messages(), 400)
                      ->header('Content-Type', 'application/json');
        }

        if ($request->amount<=0) {
            return $this->response_400('Amount <= 0');
        }
        
        return false;
    }
    
    /*
     * Create refill client balance transaction
     */
    private function createWalletTransaction($wallet, $amount){
        if(is_null($wallet)){
            return $this->response_400('Wallet not exists');
        }
        
        $tran = new Transaction;
        $tran->wallet_id_to = $wallet->wallet_id;
        $tran->currency_id = $wallet->currency_id;
        $tran->amount = $amount;
        $tran->operation = 'REFILL';
        $tran->tran_date = date('Y-m-d H:i:s');
        
        if(!ExchangeRateRepository::rateExists($tran->currency_id, $tran->tran_date)){
            return $this->response_400('Exchange rate not exists');
        }
        
        DB::beginTransaction();
        $tran->save();
        DB::commit();
        
        return response('Transactions ' . $tran->operation . ' created', 200)
                  ->header('Content-Type', 'text/plain');
    }
    
    /*
     * Run client to client transaction
     */
    public function clientTransferTransaction(Request $request, $name_from, $name_to){
        $response = $this->validateRequest($request, [
            'amount' => 'required|numeric',
            'currency_use' => 'required|max:4',
        ]);
        if ($response) {
            return $response;
        }
        
        $wallet_from = ClientRepository::getWalletUncommited($name_from);
        $wallet_to = ClientRepository::getWalletUncommited($name_to);
        return $this->createTransferTransaction($wallet_from, $wallet_to, $request);
    }
    
    /*
     * Run wallet to wallet transaction
     */
    public function walletTransferTransaction(Request $request, $wallet_id_from, $wallet_id_to){
        $response = $this->validateRequest($request, [
            'amount' => 'required|numeric',
            'currency_use' => 'required|max:4',
        ]);
        if ($response) {
            return $response;
        }
        
        $wallet_from = WalletRepository::getWalletUncommited($wallet_id_from);
        $wallet_to = WalletRepository::getWalletUncommited($wallet_id_to);
        return $this->createTransferTransaction($wallet_from, $wallet_to, $request);
    }
    
    /*
     * Create transfer transaction
     */
    private function createTransferTransaction($wallet_from, $wallet_to, $request){
        if(is_null($wallet_from)){
            return $this->response_400('Wallet_from not exists');
        }
        if(is_null($wallet_to)){
            return $this->response_400('Wallet_to not exists');
        }
        if($wallet_from->wallet_id == $wallet_to->wallet_id){
            return $this->response_400('Transfer himself');
        }
        if($request->currency_use!='FROM' && $request->currency_use!='TO'){
            return $this->response_400('Set currency_use FROM or TO');
        }
        
        $tran = new Transaction;
        $tran->wallet_id_from = $wallet_from->wallet_id;
        $tran->wallet_id_to = $wallet_to->wallet_id;
        $tran->currency_id = ($request->currency_use=='FROM' ? $wallet_from->currency_id : $wallet_to->currency_id);
        $tran->amount = $request->amount;
        $tran->operation = 'TRANSFER';
        $tran->tran_date = date('Y-m-d H:i:s');
        
        if(!ExchangeRateRepository::rateExists($tran->currency_id, $tran->tran_date)){
            return $this->response_400('Exchange rate not exists');
        }
        
        DB::beginTransaction();
        $tran->save();
        DB::commit();
        
        return response('Transactions ' . $tran->operation . ' created', 200)
                  ->header('Content-Type', 'text/plain');
    }
    
    private function response_400($msg){
         return response($msg, 400)
                      ->header('Content-Type', 'text/plain');
    }
}
