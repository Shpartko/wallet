<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Client;
use App\Repositories\ClientRepository;
use App\Repositories\CurrencyDictRepository;
use App\Wallet;

class ClientController extends Controller
{
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'country' => 'required|max:60',
            'city' => 'required|max:180',
            'currency' => 'required|max:3',
        ]);

        if ($validator->fails()) {
            return response($validator->messages(), 400)
                      ->header('Content-Type', 'application/json');
        }
        
        if(ClientRepository::clientExists($request->name)){
            return response('Client exists', 400)
                      ->header('Content-Type', 'text/plain');
        }
        
        $currency=CurrencyDictRepository::getCurrency($request->currency);
        if(is_null($currency)){
            return response('Currency not exists', 400)
                      ->header('Content-Type', 'text/plain');
        }
        
        DB::beginTransaction();
        
        $client = new Client;
        $client->name = $request->name;
        $client->country = $request->country;
        $client->city = $request->city;
        $client->reg_date = date('Y-m-d H:i:s');
        $client->save();

        $wallet = new Wallet;
        $wallet->client_id = $client->client_id;
        $wallet->currency_id = $currency->currency_id;
        $wallet->balance = 0;
        $wallet->save();
        
        DB::commit();
        
        return response('Client created', 200)
                  ->header('Content-Type', 'text/plain');
    }
}
