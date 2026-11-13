<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterApbdAccount extends Model
{
    protected $table = 'master_apbd_accounts';

    protected $fillable = [
        'type',
        'category_code',
        'category_name',
        'code',
        'name',
    ];

    public function records()
    {
        return $this->hasMany(ApbdAccountRecord::class, 'account_id');
    }
}