<?php

namespace App\Models;

use App\Exceptions\DESMissingQuestions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Surveys\Patient\Steps\Milestone\DES;
use Carbon\CarbonInterval;

trait CanTimeTravel
{
    public function timeTravel(CarbonInterval $interval)
    {
        foreach ($this->keys() as $field)
        {
            if (! is_null($field)) {
                $this->setAttribute(
                $field,
                $this->getAttribute($field)?->sub($interval));
            }
        }
        $this->save();

        return $this;
    }

    private function keys()
    {
        return collect($this->attributes)
                ->keys()
                ->filter(fn ($key) => $this->isDateAttribute($key))
                ->values()
                ->toArray();
    }
}
