<?php

namespace App\Repositories;

use App\Wallet_hist;

class WalletHistRepository
{
    public static function getWalletHist($wallet_id, $date_from = null, $date_to = null)
    {        
        if($date_from == '' && $date_to == ''){
            $wallet_hist = Wallet_hist::where('wallet_id', $wallet_id);
        }  
        if($date_from !='' && $date_to !=''){
            $wallet_hist = Wallet_hist::where('wallet_id', $wallet_id)
                    ->whereBetween('hist_date', [$date_from, $date_to]);
        }
        if($date_from !=''){
            $wallet_hist = Wallet_hist::where('wallet_id', $wallet_id)
                    ->where('hist_date', '>=', $date_from);
        }
        if($date_to !=''){
            $wallet_hist = Wallet_hist::where('wallet_id', $wallet_id)
                    ->where('hist_date', '<=',  $date_to);
        }
        
        return $wallet_hist->with('operation')->orderBy('hist_date')->get();
    }
}
