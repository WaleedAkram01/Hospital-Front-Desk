<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Specialization;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use App\Models\Test;
use App\Models\Surgery;
use App\Models\Department;
use App\Models\Bed;
use App\Models\Patient;
use App\Models\PatientAdmission;
use App\Models\PatientClearance;
use App\Models\Attendant;

class HospitalSeeder extends Seeder
{
    public function run()
    {
        // 1. Specializations
        $specializations = [
            ['name' => 'Cardiology', 'description' => 'Heart and cardiovascular system'],
            ['name' => 'Neurology', 'description' => 'Brain and nervous system'],
            ['name' => 'Orthopedics', 'description' => 'Bones, joints, and muscles'],
            ['name' => 'Pediatrics', 'description' => 'Children health care'],
            ['name' => 'General Surgery', 'description' => 'Surgical procedures'],
            ['name' => 'Internal Medicine', 'description' => 'Internal organ diseases'],
        ];

        foreach ($specializations as $spec) {
            Specialization::create($spec);
        }

        // 2. Doctors

        $doctors = [
            ['specialization_id' => 1, 'name' => 'Dr. Ahmed Ali', 'qualification' => 'MBBS, FCPS (Cardiology)', 'phone' => '0300-1234567', 'email' => 'ahmed@hospital.com', 'consultation_fee' => 2000],
            ['specialization_id' => 1, 'name' => 'Dr. Sarah Khan', 'qualification' => 'MBBS, MD (Cardiology)', 'phone' => '0301-2345678', 'email' => 'sarah@hospital.com', 'consultation_fee' => 2500],
            ['specialization_id' => 2, 'name' => 'Dr. Hassan Raza', 'qualification' => 'MBBS, FCPS (Neurology)', 'phone' => '0302-3456789', 'email' => 'hassan@hospital.com', 'consultation_fee' => 2200],
            ['specialization_id' => 3, 'name' => 'Dr. Fatima Malik', 'qualification' => 'MBBS, FRCS (Orthopedics)', 'phone' => '0303-4567890', 'email' => 'fatima@hospital.com', 'consultation_fee' => 1800],
            ['specialization_id' => 4, 'name' => 'Dr. Zainab Ahmad', 'qualification' => 'MBBS, DCH (Pediatrics)', 'phone' => '0304-5678901', 'email' => 'zainab@hospital.com', 'consultation_fee' => 1500],
            ['specialization_id' => 5, 'name' => 'Dr. Usman Tariq', 'qualification' => 'MBBS, FRCS (Surgery)', 'phone' => '0305-6789012', 'email' => 'usman@hospital.com', 'consultation_fee' => 2500],
        ];

        foreach ($doctors as $doc) {
            Doctor::create($doc);
        }

        // 3. Doctor Schedules
        $schedules = [
            ['doctor_id' => 1, 'day' => 'Monday', 'start_time' => '09:00:00', 'end_time' => '14:00:00'],
            ['doctor_id' => 1, 'day' => 'Wednesday', 'start_time' => '09:00:00', 'end_time' => '14:00:00'],
            ['doctor_id' => 1, 'day' => 'Friday', 'start_time' => '09:00:00', 'end_time' => '14:00:00'],
            ['doctor_id' => 2, 'day' => 'Tuesday', 'start_time' => '14:00:00', 'end_time' => '18:00:00'],
            ['doctor_id' => 2, 'day' => 'Thursday', 'start_time' => '14:00:00', 'end_time' => '18:00:00'],
            ['doctor_id' => 3, 'day' => 'Monday', 'start_time' => '10:00:00', 'end_time' => '15:00:00'],
            ['doctor_id' => 3, 'day' => 'Thursday', 'start_time' => '10:00:00', 'end_time' => '15:00:00'],
            ['doctor_id' => 4, 'day' => 'Wednesday', 'start_time' => '08:00:00', 'end_time' => '13:00:00'],
            ['doctor_id' => 4, 'day' => 'Saturday', 'start_time' => '08:00:00', 'end_time' => '13:00:00'],
            ['doctor_id' => 5, 'day' => 'Monday', 'start_time' => '15:00:00', 'end_time' => '19:00:00'],
            ['doctor_id' => 5, 'day' => 'Tuesday', 'start_time' => '15:00:00', 'end_time' => '19:00:00'],
            ['doctor_id' => 5, 'day' => 'Wednesday', 'start_time' => '15:00:00', 'end_time' => '19:00:00'],
        ];

        foreach ($schedules as $schedule) {
            DoctorSchedule::create($schedule);
        }

        // 4. Tests
        $tests = [
            ['name' => 'Complete Blood Count (CBC)', 'rate' => 500, 'reporting_time_hours' => 6, 'department' => 'Hematology'],
            ['name' => 'Lipid Profile', 'rate' => 800, 'reporting_time_hours' => 24, 'department' => 'Biochemistry'],
            ['name' => 'Liver Function Test', 'rate' => 1200, 'reporting_time_hours' => 24, 'department' => 'Biochemistry'],
            ['name' => 'ECG', 'rate' => 600, 'reporting_time_hours' => 1, 'department' => 'Cardiology'],
            ['name' => 'X-Ray Chest', 'rate' => 1000, 'reporting_time_hours' => 4, 'department' => 'Radiology'],
            ['name' => 'MRI Scan', 'rate' => 15000, 'reporting_time_hours' => 48, 'department' => 'Radiology'],
            ['name' => 'CT Scan', 'rate' => 12000, 'reporting_time_hours' => 24, 'department' => 'Radiology'],
            ['name' => 'Ultrasound', 'rate' => 2000, 'reporting_time_hours' => 6, 'department' => 'Radiology'],
            ['name' => 'Blood Sugar (Fasting)', 'rate' => 300, 'reporting_time_hours' => 6, 'department' => 'Biochemistry'],
            ['name' => 'HbA1c', 'rate' => 1500, 'reporting_time_hours' => 24, 'department' => 'Biochemistry'],
        ];

        foreach ($tests as $test) {
            Test::create($test);
        }

        // 5. Surgeries
        $surgeries = [
            ['name' => 'Appendectomy', 'rate' => 50000, 'estimated_duration_hours' => 2, 'department' => 'General Surgery'],
            ['name' => 'Hernia Repair', 'rate' => 60000, 'estimated_duration_hours' => 3, 'department' => 'General Surgery'],
            ['name' => 'Cesarean Section', 'rate' => 80000, 'estimated_duration_hours' => 2, 'department' => 'Gynecology'],
            ['name' => 'Knee Replacement', 'rate' => 200000, 'estimated_duration_hours' => 4, 'department' => 'Orthopedics'],
            ['name' => 'Cataract Surgery', 'rate' => 40000, 'estimated_duration_hours' => 1, 'department' => 'Ophthalmology'],
            ['name' => 'Angioplasty', 'rate' => 300000, 'estimated_duration_hours' => 3, 'department' => 'Cardiology'],
        ];

        foreach ($surgeries as $surgery) {
            Surgery::create($surgery);
        }

        // 6. Departments
        $departments = [
            ['name' => 'General Ward', 'location' => 'Ground Floor'],
            ['name' => 'Private Ward', 'location' => 'First Floor'],
            ['name' => 'ICU', 'location' => 'Second Floor'],
            ['name' => 'Pediatric Ward', 'location' => 'First Floor'],
            ['name' => 'Maternity Ward', 'location' => 'Ground Floor'],
        ];

        foreach ($departments as $dept) {
            Department::create($dept);
        }

        // 7. Beds - Create 10 beds per department
        foreach (Department::all() as $dept) {
            for ($i = 1; $i <= 10; $i++) {
                Bed::create([
                    'department_id' => $dept->id,
                    'bed_number' => $dept->name . '-' . str_pad($i, 2, '0', STR_PAD_LEFT),
                    'status' => 'available',
                    'is_active' => true
                ]);
            }
        }

        // 8. Sample Patients
        $patients = [
            ['mr_number' => 'MR-2024-001', 'name' => 'Muhammad Aslam', 'father_name' => 'Abdul Rahman', 'age' => 45, 'gender' => 'Male', 'phone' => '0321-1234567', 'cnic' => '35202-1234567-1', 'address' => 'Model Town, Lahore', 'status' => 'admitted', 'admission_date' => now()->subDays(5)],
            ['mr_number' => 'MR-2024-002', 'name' => 'Ayesha Bibi', 'father_name' => 'Muhammad Yousaf', 'age' => 32, 'gender' => 'Female', 'phone' => '0322-2345678', 'cnic' => '35202-2345678-2', 'address' => 'Gulberg, Karachi', 'status' => 'admitted', 'admission_date' => now()->subDays(3)],
            ['mr_number' => 'MR-2024-003', 'name' => 'Ali Hassan', 'father_name' => 'Hassan Ali', 'age' => 28, 'gender' => 'Male', 'phone' => '0323-3456789', 'cnic' => '35202-3456789-3', 'address' => 'F-10, Islamabad', 'status' => 'admitted', 'admission_date' => now()->subDays(2)],
            ['mr_number' => 'MR-2024-004', 'name' => 'Fatima Noor', 'father_name' => 'Noor Ahmed', 'age' => 25, 'gender' => 'Female', 'phone' => '0324-4567890', 'cnic' => '35202-4567890-4', 'address' => 'Samanabad, Faisalabad', 'status' => 'admitted', 'admission_date' => now()->subDays(1)],
            ['mr_number' => 'MR-2024-005', 'name' => 'Bilal Khan', 'father_name' => 'Khan Sahib', 'age' => 50, 'gender' => 'Male', 'phone' => '0325-5678901', 'cnic' => '35202-5678901-5', 'address' => 'Cantt, Multan', 'status' => 'opd'],
        ];

        foreach ($patients as $patient) {
            Patient::create($patient);
        }

        // 9. Patient Admissions with Bed Occupancy

        // General Ward - Bed 01
        $generalBed = Bed::where('department_id', 1)->where('bed_number', 'General Ward-01')->first();
        if ($generalBed) {
            PatientAdmission::create([
                'patient_id' => 1,
                'bed_id' => $generalBed->id,
                'doctor_id' => 1,
                'department_id' => 1,
                'admission_date' => now()->subDays(5),
                'reason' => 'Chest pain and breathing difficulty',
                'diagnosis' => 'Suspected cardiac issue under observation',
                'status' => 'active'
            ]);
            $generalBed->update(['status' => 'occupied']);
        }

        // Private Ward - Bed 01
        $privateBed = Bed::where('department_id', 2)->where('bed_number', 'Private Ward-01')->first();
        if ($privateBed) {
            PatientAdmission::create([
                'patient_id' => 2,
                'bed_id' => $privateBed->id,
                'doctor_id' => 5,
                'department_id' => 2,
                'admission_date' => now()->subDays(3),
                'reason' => 'Normal delivery',
                'diagnosis' => 'Post-natal care and recovery',
                'status' => 'active'
            ]);
            $privateBed->update(['status' => 'occupied']);
        }

        // ICU - Bed 01
        $icuBed = Bed::where('department_id', 3)->where('bed_number', 'ICU-01')->first();
        if ($icuBed) {
            PatientAdmission::create([
                'patient_id' => 3,
                'bed_id' => $icuBed->id,
                'doctor_id' => 2,
                'department_id' => 3,
                'admission_date' => now()->subDays(2),
                'reason' => 'Head injury from road accident',
                'diagnosis' => 'Severe concussion - critical monitoring',
                'status' => 'active'
            ]);
            $icuBed->update(['status' => 'occupied']);
        }

        // Maternity Ward - Bed 01
        $maternityBed = Bed::where('department_id', 5)->where('bed_number', 'Maternity Ward-01')->first();
        if ($maternityBed) {
            PatientAdmission::create([
                'patient_id' => 4,
                'bed_id' => $maternityBed->id,
                'doctor_id' => 5,
                'department_id' => 5,
                'admission_date' => now()->subDays(1),
                'reason' => 'C-Section delivery',
                'diagnosis' => 'Post-operative care',
                'status' => 'active'
            ]);
            $maternityBed->update(['status' => 'occupied']);
        }

        // 10. Create clearance records
        foreach (Patient::where('status', 'admitted')->get() as $patient) {
            PatientClearance::create([
                'patient_id' => $patient->id
            ]);
        }

        // 11. Create Attendants with STATIC card numbers
        Attendant::create([
            'patient_id' => 1,
            'name' => 'Abdul Rehman',
            'relation' => 'Son',
            'cnic' => '35202-7777777-7',
            'phone' => '0333-7777777',
            'address' => 'Model Town, Lahore',
            'card_number' => 'ATN-2024-001',
            'status' => 'in',
            'last_in_time' => now()
        ]);

        Attendant::create([
            'patient_id' => 2,
            'name' => 'Muhammad Yousaf',
            'relation' => 'Husband',
            'cnic' => '35202-8888888-8',
            'phone' => '0334-8888888',
            'address' => 'Gulberg, Karachi',
            'card_number' => 'ATN-2024-002',
            'status' => 'in',
            'last_in_time' => now()
        ]);

        Attendant::create([
            'patient_id' => 3,
            'name' => 'Fatima Hassan',
            'relation' => 'Sister',
            'cnic' => '35202-9999999-9',
            'phone' => '0335-9999999',
            'address' => 'F-10, Islamabad',
            'card_number' => 'ATN-2024-003',
            'status' => 'out',
            'last_out_time' => now()->subHours(2)
        ]);
    }
}
