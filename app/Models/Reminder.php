<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reminder extends Model
{
    use HasFactory, CanTimeTravel;

    const CREATED_AT = 'sent_at';
    const UPDATED_AT = null;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $casts = [
        'sent_at' => 'immutable_datetime'
    ];

    protected $fillable = [
        'type','week'
    ];

    public function test()
    {
        foreach($this->attributes as $key => $value) {
            if ($this->isDateAttribute($key)) {
                echo $this->asDateTime($value);
            }
        }
    }
}
