<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    use HasFactory;

    protected $table = 'prescriptions';
    protected $fillable = [
        'doctor_id',
        'patient_id',
        'name',
        'description',
        'quantity',
        'dosage',
    ];
}
