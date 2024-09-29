<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\File;


class MedicalHistory extends Model
{
    use HasFactory;

    protected $table = 'medical_histories';

    protected $fillable = [
        'patient_id',
        'description',
        'diagnosis',
        'treatment',
        'doctor_id'
    ];

    public function files() {
        return $this->hasMany(File::class, 'medical_history_id');
    }
}
