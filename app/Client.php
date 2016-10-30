<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model {

    public $timestamps = false;
    public $primaryKey = 'client_id';
    
    public function wallet() {
        return $this->hasOne(Wallet::class, 'client_id', 'client_id');
    }

}
