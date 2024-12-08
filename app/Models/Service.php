<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\UsesUuid;

class Service extends Model
{
    use UsesUuid;

    protected $table = 'services';

    protected $fillable = [
        'service_name',
        'description',
        'price'
    ];
}
