<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Emonev extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'emonev';
    protected $primaryKey = 'pkid';

    protected $fillable = [
        'uuid',
        'departement_id',
        'tahun',
        'bulan',
        'anggaran',
        'fisik',
        'gap',
        'kinerja_satker',
        'keterangan',
    ];

    public function departement()
    {
        return $this->belongsTo(Departement::class, 'departement_id', 'pkid');
    }
}
