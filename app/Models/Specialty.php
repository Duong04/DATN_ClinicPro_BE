<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Doctor;

class Specialty extends Model
{
    use HasFactory, UsesUuid;

    protected $table = 'specialties';

    protected $fillable = [
        'name',
        'description',
    ];
    
    public function doctors() {
        return $this->hasMany(Doctor::class, 'specialty_id');
    }
}
