<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Models\Activity as SpatieActivity;

class Activity extends SpatieActivity
{
    use HasFactory;

    protected $casts = [
        'properties' => 'array',
    ];

    protected $appends = [
        'summary',
    ];

    protected function summary(): Attribute
    {
        return Attribute::get(function (): string {
            $description = $this->description;
            $subject = class_basename((string) $this->subject_type);

            return trim($description . ' ' . ($this->event ? '[' . $this->event . ']' : '') . ' ' . $subject);
        });
    }
}
