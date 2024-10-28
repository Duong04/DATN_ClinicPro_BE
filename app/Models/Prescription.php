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

    public function prescription_Infos()
    {
        return $this->hasMany(PrescriptionInfo::class, 'prescription_id');
    }
}
