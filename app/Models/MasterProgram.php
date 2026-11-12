<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterProgram extends Model
{
    protected $table = 'master_programs';

    protected $fillable = [
        'sector_id',
        'parent_id',
        'code',
        'name',
        'level',
    ];

    // Relasi ke Sektor/Urusan (Pendidikan, Kesehatan, dll.)
    public function sector()
    {
        return $this->belongsTo(Sector::class, 'sector_id');
    }

    // Relasi ke Program Induk (untuk Sub-Kegiatan Level 2)
    public function parent()
    {
        return $this->belongsTo(MasterProgram::class, 'parent_id');
    }

    // Relasi ke Daftar Sub-Kegiatan (untuk Program Utama Level 1)
    public function children()
    {
        return $this->hasMany(MasterProgram::class, 'parent_id');
    }

    // Relasi ke Data Anggaran Tahunan
    public function expenditures()
    {
        return $this->hasMany(ProgramExpenditure::class, 'program_id');
    }
}