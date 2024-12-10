<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\File;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\User;
use App\Models\Service;

class MedicalHistory extends Model
{
    use HasFactory, UsesUuid;

    protected $table = 'medical_histories';

    protected $fillable = [
        'patient_id',
        'description',
        'diagnosis',
        'treatment',
        'user_id',
        'indication'
    ];

    public function files()
    {
        return $this->hasMany(File::class, 'medical_history_id');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function prescriptions()
    {
        return $this->hasMany(Prescription::class, 'medical_histories_id');
    }
    public function services()
    {
        return $this->belongsToMany(Service::class, 'service_medical_history', 'medical_history_id', 'service_id');
    }
}
