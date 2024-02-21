<?php

namespace App\Models;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Events extends Model
{
    protected $fillable = [
        'title',
        'event_id',
        'description',
        'date',
        'duration_date',
        'due_date',
        'payment',
        'academic_year',
        'semester',
        'status'
    ];
    
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($events) {
            $events->event_id = static::generateTransactionId();
        });
    }

    private static function generateTransactionId()
    {
        $randomString = Str::random(7);

        $iteration = 1; 

        $eventId = "EVENT-{$randomString}{$iteration}";

        return $eventId;
    }

    use HasFactory;
}
