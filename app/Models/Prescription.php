<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Prescription extends Model
{
    use HasFactory, UsesUuid;

    protected $table = 'prescriptions';
    protected $fillable = [
        'user_id',
        'patient_id',
        'name',
        'description'
    ];
    public $incrementing = false;
    protected $keyType = 'string';

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->id = 'RX-' . time() . '-' . strtoupper(Str::random(6));
        });
    }

    public function prescription_Infos()
    {
        return $this->hasMany(PrescriptionInfo::class, 'prescription_id');
    }
}
