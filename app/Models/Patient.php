<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PatientInfo;
use App\Models\IdentityCard;
use App\Models\MedicalHistory;

class Patient extends Model
{
    use HasFactory, UsesUuid;

    protected $table = 'patients';

    protected $fillable = [
        'status',
        'insurance_number',
        'identity_card_id',
        'user_id',
    ];

    public function patientInfo()
    {
        return $this->hasOne(PatientInfo::class, 'patient_id');
    }

    public function identityCard()
    {
        return $this->belongsTo(IdentityCard::class, 'identity_card_id');
    }

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class, 'patient_id');
    }

    public function medicalHistories() {
        return $this->hasMany(MedicalHistory::class, 'patient_id');
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
