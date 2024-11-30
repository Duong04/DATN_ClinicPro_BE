<?php

namespace App\Models;

use App\Models\User;
use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Appointment extends Model
{
    use HasFactory, UsesUuid;

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
        'specialty_id',
        'package_id',
        'description'
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

    public function package()
    {
        return $this->belongsTo(Package::class);
    }
    public function specialty()
    {
        return $this->belongsTo(Specialty::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
