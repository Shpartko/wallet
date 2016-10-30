<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Wallet_hist extends Model
{
    public $timestamps = false;
    public $primaryKey='hist_id';
  
    public function operation() {
        return $this->hasOne(Operation::class, 'oper_id', 'oper_id');
    }
  
    public function currency_dict() {
        return $this->hasOne(Operation::class, 'currency_id', 'currency_id');
    }
    
    public function wallet() {
        return $this->belongsTo(Wallet::class, 'wallet_id', 'wallet_id');
    }
    
    public function wallet_partner() {
        return $this->hasOne(Wallet::class, 'wallet_id', 'wallet_partner_id');
    }
}
