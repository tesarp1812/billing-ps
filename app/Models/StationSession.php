<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StationSession extends Model
{
    use HasFactory;

    protected $table = 'sessions';

    protected $fillable = [
        'station_id',
        'user_id',
        'customer_name',
        'start_time',
        'end_time',
        'duration_minutes',
        'subtotal',
        'status',
        'billing_type',
        'package_minutes',
        'package_end_time',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'package_end_time' => 'datetime',
        'subtotal' => 'decimal:2',
    ];

    public function station()
    {
        return $this->belongsTo(Station::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
