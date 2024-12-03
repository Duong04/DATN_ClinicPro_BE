<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UsesUuid;

class Conversation extends Model
{
    use HasFactory, UsesUuid;

    protected $table = 'conversations';

    protected $fillable = [
        'user_id',
        'title'
    ];

    public function messages() {
        return $this->hasMany(Message::class, 'conversation_id');
    }
}
