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
        'description',
        'medical_histories_id'
    ];

    public function prescription_Infos()
    {
        return $this->hasMany(PrescriptionInfo::class, 'prescription_id');
    }
    public function medical_histories()
    {
        return $this->belongsTo(MedicalHistory::class, 'medical_histories_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
