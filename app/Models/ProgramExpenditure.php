<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramExpenditure extends Model
{
    protected $table = 'program_expenditures';

    protected $fillable = [
        'regency_id',
        'program_id',
        'year',
        'pegawai_expense',
        'barang_jasa_expense',
        'modal_expense',
        'total_expense',
    ];

    // Relasi ke Wilayah Kabupaten/Kota
    public function regency()
    {
        return $this->belongsTo(Regency::class, 'regency_id');
    }

    // Relasi ke Master Program/Kegiatan
    public function program()
    {
        return $this->belongsTo(MasterProgram::class, 'program_id');
    }
}