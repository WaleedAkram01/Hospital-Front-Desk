<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'mr_number',
        'name',
        'father_name',
        'age',
        'gender',
        'phone',
        'cnic',
        'address',
        'admission_date',
        'discharge_date',
        'status'
    ];

    protected $casts = [
        'admission_date' => 'date',
        'discharge_date' => 'date'
    ];

    public function admissions()
    {
        return $this->hasMany(PatientAdmission::class);
    }

    public function currentAdmission()
    {
        return $this->hasOne(PatientAdmission::class)
            ->where('status', 'active')
            ->latest();
    }

    public function attendants()
    {
        return $this->hasMany(Attendant::class);
    }

    public function clearance()
    {
        return $this->hasOne(PatientClearance::class);
    }
}
