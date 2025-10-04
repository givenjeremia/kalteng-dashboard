<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Budget extends Model
{
    use HasFactory,SoftDeletes;
    protected $primaryKey = 'pkid';
    public $incrementing = true;
    protected $keyType = 'int';
    protected $fillable = [
        'departement_id',
        'pagu_pegawai',
        'realisasi_pegawai',
        'pagu_barang',
        'realisasi_barang',
        'pagu_modal',
        'realisasi_modal',
        'tahun',
        'bulan',
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
