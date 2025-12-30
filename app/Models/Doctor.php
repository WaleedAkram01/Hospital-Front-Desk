<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    protected $fillable = [
        'specialization_id',
        'name',
        'qualification',
        'phone',
        'email',
        'consultation_fee',
        'is_active'
    ];

    protected $casts = [
        'consultation_fee' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    public function specialization()
    {
        return $this->belongsTo(Specialization::class);
    }

    public function schedules()
    {
        return $this->hasMany(DoctorSchedule::class);
    }

    public function activeSchedules()
    {
        return $this->hasMany(DoctorSchedule::class)->where('is_active', true);
    }

    // Add accessor for JSON serialization
    protected $appends = ['active_schedules'];

    public function getActiveSchedulesAttribute()
    {
        return $this->activeSchedules()->get();
    }

    public function patientAdmissions()
    {
        return $this->hasMany(PatientAdmission::class);
    }
}