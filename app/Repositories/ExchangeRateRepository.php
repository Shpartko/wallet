<?php

namespace App\Repositories;

use App\Exchange_rate;

class ExchangeRateRepository
{
    public static function getRate($currency_id, $rate_date)
    {
        return Exchange_rate::where('currency_id', $currency_id)
                      ->where('rate_date', $rate_date)->first();
    }
    
    public static function getRateCurrency($currency_id, $rate_date)
    {
        return Exchange_rate::where('currency_id', $currency_id)
                      ->where('rate_date', $rate_date)->with('currency_dict')->first();
    }
    
    public static function rateExists($currency_id, $rate_date){
        return !is_null(ExchangeRateRepository::getRate($currency_id, $rate_date));
    }
}
