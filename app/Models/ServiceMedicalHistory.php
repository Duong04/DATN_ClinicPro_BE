<?php

namespace App\Models;

use Google\Cloud\Core\Timestamp;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UsesUuid;

class ServiceMedicalHistory extends Model
{
    use UsesUuid;

    public $timestamps = false;

    protected $table = 'service_medical_history';

    protected $fillable = [
        'service_id',
        'medical_history_id'
    ];
    
}
