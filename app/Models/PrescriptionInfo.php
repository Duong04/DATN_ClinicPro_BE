<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrescriptionInfo extends Model
{
    use HasFactory;
    protected $table = 'prescription_infos';
    protected $fillable = [
        'prescription_id',
        'instructions',
        'medication_id',
        'duration',
        'quantity'
    ];

    public function medication()
    {
        return $this->belongsTo(Medication::class);
    }
}
