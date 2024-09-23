<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PatientInfo;

class Patient extends Model
{
    use HasFactory;

    protected $table = 'patients';

    protected $fillable = [
        'medical_history',
        'insurance_number',
        'identity_card_id',
        'user_id',
    ];

    public function patientInfo() {
        return $this->hasOne(PatientInfo::class, 'patient_id');
    }
}
