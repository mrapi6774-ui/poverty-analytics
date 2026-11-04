<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegionalIndicatorData extends Model
{
    protected $table = 'regional_indicator_data';
    protected $fillable = ['regency_id', 'indicator_id', 'year', 'value'];

    public function regency()
    {
        return $this->belongsTo(Regency::class);
    }

    public function indicator()
    {
        return $this->belongsTo(Indicator::class);
    }
}
