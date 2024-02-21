<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Transactions extends Model
{
    protected $fillable = [
        'title',
        'payment',
        'date',
        'duration_date',
        'academic_year',
        'semester',
        'student_number',
        'fullname',
        'paid_by',
        'relation',
        'transaction_id', 
        'event_id', 
        'status'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($transaction) {
            $transaction->transaction_id = static::generateTransactionId();
        });
    }

    private static function generateTransactionId()
    {
        $currentYear = now()->format('y'); 

        $randomString = Str::random(8);

        $transactionId = "CPV{$currentYear}-{$randomString}";

        return $transactionId;
    }

    use HasFactory;
}
