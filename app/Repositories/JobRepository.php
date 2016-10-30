<?php

namespace App\Repositories;

use App\Job;
use Illuminate\Support\Facades\DB;

class JobRepository
{
    /*
     * Use read uncommitted. Don't return balance!
     */
    public static function getJobUncommited($mask)
    {
        Job::raw('SET SESSION TRANSACTION ISOLATION LEVEL READ UNCOMMITTED;');
        DB::beginTransaction();
        $job = Job::where('queue', 'like', $mask);
        DB::commit();
        Job::raw('SET SESSION TRANSACTION ISOLATION LEVEL READ COMMITTED;');
        
        return $job;
    }
}
