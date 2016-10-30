<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Client;
use App\Repositories\ClientRepository;
use App\Repositories\WalletRepository;
use App\Repositories\CurrencyDictRepository;
use App\Repositories\WalletHistRepository;

class WalletHistController extends Controller
{
    public function welcome()
    {
        $clients = ClientRepository::getLimitedClient(50);
        
        return view('clients.index', [
            'clients' => $clients,
            'client_id' => null,
            'date_from' => '',
            'date_to' => '',
            'wallet_hist' => null,
          ]);
    }
    
    public function report(Request $request, $client_id)
    {
        $client = Client::find($client_id);
        if(is_null($client)){
            return response('Client not exists', 400)
                      ->header('Content-Type', 'text/plain');
        }
        
        $validator = Validator::make($request->all(), [
            'date_from' => 'date',
            'date_to' => 'date',
        ]);
        if ($validator->fails()) {
            return response($validator->messages(), 400)
                      ->header('Content-Type', 'application/json');
        }
        
        $clients = ClientRepository::getLimitedClient(50);
        $wallet = WalletRepository::getWalletForClientUncommited($client->client_id);
        $currency = CurrencyDictRepository::find($wallet->currency_id);
        $wallet_hist = WalletHistRepository::getWalletHist($wallet->wallet_id
                , $this->convert_date($request->date_from)
                , $this->convert_date($request->date_to));
        
        $sum_usd = $wallet_hist->sum('operation.usd_amount');
        $sum_amount = $wallet_hist->sum('amount');
        
        return view('clients.index', [
            'clients' => $clients,
            'client' => $client,
            'client_id' => $client->client_id,
            'currency' => $currency,
            'wallet_hist' => $wallet_hist,
            'sum_usd' => $sum_usd,
            'sum_amount' => $sum_amount,
            'date_from' => $request->date_from,
            'date_to' => $request->date_to,
          ]);
    }
    
    private function convert_date($date){
        return $date == '' ? '' : date('Y-m-d H:i', strtotime($date));
    }
}
