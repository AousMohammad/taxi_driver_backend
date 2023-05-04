<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carowner extends Model
{
    use HasFactory;

    protected $fillable = [
        'driver_id',
        'model',
        'location',
        'in_time',
        'out_time',
        'free'
    ];

    public function getCreatedAtAttribute($value)
    {
        return Carbon::createFromTimestamp(strtotime($value))
            ->timezone('Asia/Damascus')
            ->toDateTimeString();
    }

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::createFromTimestamp(strtotime($value))
            ->timezone('Asia/Damascus')
            ->toDateTimeString();
    }
}
