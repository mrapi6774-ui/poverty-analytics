<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApbdAccountRecord extends Model
{
    protected $table = 'apbd_account_records';

    protected $fillable = [
        'regency_id',
        'account_id',
        'year',
        'amount',
    ];

    public function regency()
    {
        return $this->belongsTo(Regency::class, 'regency_id');
    }

    public function account()
    {
        return $this->belongsTo(MasterApbdAccount::class, 'account_id');
    }
}