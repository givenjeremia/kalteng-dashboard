<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

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
        'sasaran',
        'indikator',
        'satuan',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    public function departement()
    {
        return $this->belongsTo(Departement::class, 'departement_id', 'pkid');
    }
}
