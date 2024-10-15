<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $table = 'appointments';
    protected $fillable = [
        'patient_id',
        'user_id',
        'appointment_date',
        'deposit_amount',
        'booking_type',
        'appointment_type',
        'total_amount',
        'status',
        'cancellation_reason',
        'specialty_id'
    ];

    protected $attributes = [
        'booking_type' => 'online',
    ];

    protected $hidden = [
        'deleted_at'
    ];

    protected function invoices()
    {
        return $this->hasOne(Invoices::class, 'appointment_id');
    }

    protected function payments()
    {
        return $this->hasOne(Payment::class, 'appointment_id');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
