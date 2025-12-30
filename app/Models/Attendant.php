<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendant extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'name',
        'relation',
        'cnic',
        'phone',
        'address',
        'card_number',
        'status',
        'last_in_time',
        'last_out_time'
    ];

    protected $casts = [
        'last_in_time' => 'datetime',
        'last_out_time' => 'datetime'
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function logs()
    {
        return $this->hasMany(AttendantLog::class);
    }

    public function latestLog()
    {
        return $this->hasOne(AttendantLog::class)->latest('action_time');
    }
}
