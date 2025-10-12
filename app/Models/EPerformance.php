<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EPerformance extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'e_performances';
    protected $primaryKey = 'pkid';

    protected $fillable = [
        'uuid',
        'departement_id',
        'tahun',
        'bulan',
        'target',
        'tercapai',
        'tidak_tercapai',
        'persentase_capaian',
    ];

    public function departement()
    {
        return $this->belongsTo(Departement::class, 'departement_id', 'pkid');
    }
}
