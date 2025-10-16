<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class IKPAScore extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ikpa_scores';
    protected $primaryKey = 'pkid';

    protected $fillable = [
        'uuid',
        'departement_id',
        'tahun',
        'bulan',
        'deviation_dipa',
        'revisi_dipa',
        'penyerapan_anggaran',
        'capaian_output',
        'penyelesaian_tagihan',
        'pengelolaan_up_tup',
        'belanja_kontraktual',
        'nilai_ikpa',
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
