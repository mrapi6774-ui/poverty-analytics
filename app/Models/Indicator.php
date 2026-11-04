<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Indicator extends Model
{
    protected $table = 'master_indicators'; // Tambahkan baris ini
    protected $fillable = ['sector_id', 'code', 'name', 'unit', 'direction'];

    public function sector()
    {
        return $this->belongsTo(Sector::class);
    }
}
