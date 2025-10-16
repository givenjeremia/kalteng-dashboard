<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Emonev extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'emonev';
    protected $primaryKey = 'pkid';
    public $incrementing = true;
    protected $keyType = 'int';

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
