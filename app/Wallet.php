<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    public $timestamps = false;
    public $primaryKey='wallet_id';
    
    public function client() {
        return $this->belongsTo(Client::class, 'client_id', 'client_id');
    }
    
    public function currency_dict() {
        return $this->hasOne(Currency_dict::class, 'currency_id', 'currency_id');
    }
    
    public function wallet_hist() {
        return $this->hasMany(Wallet_hist::class, 'wallet_id', 'wallet_id');
    }
    
    /*public function wallet_partner() {
        return $this->belongsTo(Wallet_hist::class, 'wallet_partner_id', 'wallet_id');
    }*/
    
    public function exchange_rate() {
        return $this->hasMany(Exchange_rate::class, 'currency_id', 'currency_id');
    }
}
