<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApbdExpenditure extends Model
{
    protected $fillable = ['regency_id', 'sector_id', 'year', 'direct_expense', 'indirect_expense', 'total_expense'];

    public function regency()
    {
        return $this->belongsTo(Regency::class);
    }

    public function sector()
    {
        return $this->belongsTo(Sector::class);
    }
}
