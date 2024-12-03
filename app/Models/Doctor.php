<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Specialty;

class Doctor extends Model
{
    use HasFactory, UsesUuid;

    protected $table = 'doctors';

    protected $fillable = [
        'specialty_id',
        'user_id'
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function specialty() {
        return $this->belongsTo(Specialty::class, 'specialty_id');
    }
}
