<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use App\Wallet;

class WalletRepository
{   
    /*
     * Use read uncommitted. Don't return balance!
     */
    public static function getWalletUncommited($wallet_id)
    {
        Wallet::raw('SET SESSION TRANSACTION ISOLATION LEVEL READ UNCOMMITTED;');
        DB::beginTransaction();
        $wallet = Wallet::where('wallet_id',$wallet_id)->get()->except(['balance'])->first();
        DB::commit();
        Wallet::raw('SET SESSION TRANSACTION ISOLATION LEVEL READ COMMITTED;');
        
        return $wallet;
    }
    
    /*
     * Use read uncommitted. Don't return balance!
     */
    public static function getWalletForClientUncommited($client_id)
    {
        Wallet::raw('SET SESSION TRANSACTION ISOLATION LEVEL READ UNCOMMITTED;');
        DB::beginTransaction();
        $wallet = Wallet::where('client_id', $client_id)->get()->except(['balance'])->first();
        DB::commit();
        Wallet::raw('SET SESSION TRANSACTION ISOLATION LEVEL READ COMMITTED;');
        
        return $wallet;
    }
}
