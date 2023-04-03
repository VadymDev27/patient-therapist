<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Survey extends Model
{
    use HasFactory, CanTimeTravel;

    public $timestamps = false;

    protected $fillable = ['user_id', 'data', 'week', 'type', 'category', 'completed_at', 'started_at'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isComplete(): bool
    {
        return !is_null($this->completed_at);
    }

    protected $casts = [
        'data' => 'array',
        'started_at' => 'immutable_datetime',
        'completed_at' => 'immutable_datetime'
    ];

    public function data(string $key, mixed $default = null): mixed
    {
        return data_get($this->data, $key, $default);
    }

    public function nameForHumans(): string
    {
        return Str::title(str_replace('-', ' ', $this->category)) . $this->endText();
    }

    private function endText(): string
    {
        if (in_array($this->category, ['prep','weekly'])) {
            return ' #' . $this->week;
        }
        if ($this->category === 'milestone') {
            return ' - ' . Str::title(str_replace('-', ' ', $this->type));
        }
        return '';
    }

    public function updateData(string $key, mixed $value): Survey
    {
        if (array_key_exists($key, $this->data)) {
            $this->data = array_merge($this->data, [ $key => $value ]);
            $this->save();
        }

        return $this;
    }
}
