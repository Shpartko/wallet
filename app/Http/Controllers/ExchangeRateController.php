<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use App\Exchange_rate;
use App\Repositories\ExchangeRateRepository;
use App\Repositories\CurrencyDictRepository;

class ExchangeRateController extends Controller
{
    function add(Request $request){
        $validator = Validator::make($request->all(), [
            'currency' => 'required|max:3',
            'rate' => 'required|numeric',
            'rate_date' => 'required|date|after:now',
        ]);

        if ($validator->fails()) {
            return response($validator->messages(), 400)
                      ->header('Content-Type', 'application/json');
        }
        
        $currency = CurrencyDictRepository::getCurrency($request->currency);
        if(is_null($currency)){
            return response('Currency not exists', 400)
                      ->header('Content-Type', 'text/plain');
        }
        
        if(ExchangeRateRepository::rateExists($currency->currency_id, $request->rate_date)){
            return response('Rate exists', 400)
                      ->header('Content-Type', 'application/plain');
        }
        
        $exchange_rate = new Exchange_rate;
        $exchange_rate->currency_id = $currency->currency_id;
        $exchange_rate->rate = $request->rate;
        $exchange_rate->rate_date = $request->rate_date;
        $exchange_rate->update_date = date('Y-m-d H:i:s');
        $exchange_rate->save();
        
        return response('Rate added', 200)
                  ->header('Content-Type', 'text/plain');
    }
}
