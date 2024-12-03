<?php

namespace App\Models;

use App\Traits\UsesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory, UsesUuid;

    protected $table = 'files';

    protected $fillable = [
        'file',
        'description',
        'medical_history_id'
    ];

    public $timestamps = false;

}
