<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Regency extends Model
{
    protected $table = 'master_regencies'; // Tambahkan baris ini
    protected $fillable = ['code', 'name', 'type'];
}
