<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sector extends Model
{
    protected $table = 'master_sectors'; // Tambahkan baris ini

    protected $fillable = ['code', 'name', 'description'];

    public function indicators()
    {
        return $this->hasMany(Indicator::class);
    }
}
