<?php

namespace App\Repositories;

use App\Currency_dict;
use Illuminate\Support\Facades\DB;

class CurrencyDictRepository
{
    public static function getCurrency($currency)
    {//Use read uncommitted. Dictionary almost unchanged
        Currency_dict::raw('SET SESSION TRANSACTION ISOLATION LEVEL READ UNCOMMITTED;');
        DB::beginTransaction();
        $res = Currency_dict::where('currency',$currency)->first();
        DB::commit();
        Currency_dict::raw('SET SESSION TRANSACTION ISOLATION LEVEL READ COMMITTED;');
        
        return $res;
    }
    
    public static function find($currency_id)
    {//Use read uncommitted. Dictionary almost unchanged
        Currency_dict::raw('SET SESSION TRANSACTION ISOLATION LEVEL READ UNCOMMITTED;');
        DB::beginTransaction();
        $res = Currency_dict::find($currency_id);
        DB::commit();
        Currency_dict::raw('SET SESSION TRANSACTION ISOLATION LEVEL READ COMMITTED;');
        
        return $res;
    }
}
