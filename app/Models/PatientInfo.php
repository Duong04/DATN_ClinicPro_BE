<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientInfo extends Model
{
    use HasFactory;

    protected $table = 'patient_infos';

    protected $fillable = [
        'fullname',
        'email',
        'phone_number',
        'address',
        'dob',
        'gender',
        'avatar',
        'patient_id',
    ];
}
