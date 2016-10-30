<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use App\Client;

class ClientRepository
{
    public static function getClient($name)
    {
        return Client::where('name',$name)->first();
    }
    
    public static function getLimitedClient($limit)
    {
        return Client::limit($limit)->get();
    }
    
    public static function getClientUncommited($name)
    {//Use read uncommitted. Worst outcome: not found client before data writing
        Client::raw('SET SESSION TRANSACTION ISOLATION LEVEL READ UNCOMMITTED;');
        DB::beginTransaction();
        $client = ClientRepository::getClient($name);
        DB::commit();
        Client::raw('SET SESSION TRANSACTION ISOLATION LEVEL READ COMMITTED;');
        
        return $client;
    }
    
    public static function getWalletUncommited($name)
    {//Use read uncommitted. Worst outcome: not found client before data writing
        $wallet = null;
                
        Client::raw('SET SESSION TRANSACTION ISOLATION LEVEL READ UNCOMMITTED;');
        DB::beginTransaction();
        $client = ClientRepository::getClient($name);
        if(!is_null($client)){
            $wallet = $client->wallet;
        }
        DB::commit();
        Client::raw('SET SESSION TRANSACTION ISOLATION LEVEL READ COMMITTED;');
        
        return $wallet;
    }
    
    public static function clientExists($name){
        return !is_null(ClientRepository::getClient($name));
    }
    
    public static function clientExistsUncommited($name){
        return !is_null(ClientRepository::getClientUncommited($name));
    }
    
    public static function getClientIdUncommited($name){
        $client = ClientRepository::getClientUncommited($name);        
        return is_null($client) ? null : $client->client_id;
    }
}
