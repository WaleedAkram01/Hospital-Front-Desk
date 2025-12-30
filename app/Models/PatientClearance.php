<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientClearance extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'billing_cleared',
        'pharmacy_cleared',
        'lab_cleared',
        'ward_cleared',
        'discharge_summary',
        'final_clearance',
        'remarks'
    ];

    protected $casts = [
        'billing_cleared' => 'boolean',
        'pharmacy_cleared' => 'boolean',
        'lab_cleared' => 'boolean',
        'ward_cleared' => 'boolean',
        'discharge_summary' => 'boolean',
        'final_clearance' => 'boolean'
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function isClearanceComplete()
    {
        return $this->billing_cleared &&
               $this->pharmacy_cleared &&
               $this->lab_cleared &&
               $this->ward_cleared &&
               $this->discharge_summary;
    }
}