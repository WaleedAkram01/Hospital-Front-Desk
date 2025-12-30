<?php

namespace App\Http\Controllers;

use App\Models\Specialization;
use App\Models\Doctor;
use App\Models\DoctorSchedule;
use App\Models\Test;
use App\Models\Surgery;
use App\Models\Patient;
use App\Models\PatientAdmission;
use App\Models\Department;
use App\Models\Bed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FrontDeskController extends Controller
{
    // Main Dashboard
    public function index()
    {
        $specializations = Specialization::where('is_active', true)->get();
        $departments = Department::where('is_active', true)->get();
        
        return view('frontdesk.index', compact('specializations', 'departments'));
    }

    // Get Doctors by Specialization
    public function getDoctorsBySpecialization($specializationId)
    {
        try {
            $doctors = Doctor::where('specialization_id', $specializationId)
                ->where('is_active', true)
                ->get();

            // Manually load schedules for each doctor
            $result = [];
            foreach ($doctors as $doctor) {
                $schedules = DoctorSchedule::where('doctor_id', $doctor->id)
                    ->where('is_active', true)
                    ->get();

                $result[] = [
                    'id' => $doctor->id,
                    'name' => $doctor->name,
                    'qualification' => $doctor->qualification,
                    'phone' => $doctor->phone,
                    'email' => $doctor->email,
                    'consultation_fee' => $doctor->consultation_fee,
                    'active_schedules' => $schedules
                ];
            }

            return response()->json($result);

        } catch (\Exception $e) {
            Log::error('Error loading doctors: ' . $e->getMessage());
            return response()->json([
                'error' => 'Error loading doctors',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Get Doctor Details
    public function getDoctorDetails($doctorId)
    {
        try {
            $doctor = Doctor::with('specialization')->findOrFail($doctorId);
            
            $schedules = DoctorSchedule::where('doctor_id', $doctorId)
                ->where('is_active', true)
                ->get();

            return response()->json([
                'id' => $doctor->id,
                'name' => $doctor->name,
                'qualification' => $doctor->qualification,
                'phone' => $doctor->phone,
                'email' => $doctor->email,
                'consultation_fee' => $doctor->consultation_fee,
                'specialization' => $doctor->specialization,
                'active_schedules' => $schedules
            ]);

        } catch (\Exception $e) {
            Log::error('Error loading doctor details: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Get Tests
    public function getTests(Request $request)
    {
        try {
            $query = Test::where('is_active', true);

            if ($request->has('search') && $request->search) {
                $query->where('name', 'like', '%' . $request->search . '%');
            }

            if ($request->has('department') && $request->department) {
                $query->where('department', $request->department);
            }

            $tests = $query->orderBy('name')->get();
            return response()->json($tests);

        } catch (\Exception $e) {
            Log::error('Error loading tests: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Get Surgeries
    public function getSurgeries(Request $request)
    {
        try {
            $query = Surgery::where('is_active', true);

            if ($request->has('search') && $request->search) {
                $query->where('name', 'like', '%' . $request->search . '%');
            }

            if ($request->has('department') && $request->department) {
                $query->where('department', $request->department);
            }

            $surgeries = $query->orderBy('name')->get();
            return response()->json($surgeries);

        } catch (\Exception $e) {
            Log::error('Error loading surgeries: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Search Patients
    public function searchPatient(Request $request)
    {
        try {
            $query = Patient::query();

            if ($request->has('search') && $request->search) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%')
                      ->orWhere('mr_number', 'like', '%' . $search . '%')
                      ->orWhere('phone', 'like', '%' . $search . '%')
                      ->orWhere('cnic', 'like', '%' . $search . '%');
                });
            }

            if ($request->has('status') && $request->status) {
                $query->where('status', $request->status);
            }

            $patients = $query->orderBy('created_at', 'desc')->get();

            // Load current admission for each patient
            $result = [];
            foreach ($patients as $patient) {
                $admission = PatientAdmission::where('patient_id', $patient->id)
                    ->where('status', 'active')
                    ->with(['department', 'doctor', 'bed'])
                    ->first();

                $result[] = [
                    'id' => $patient->id,
                    'name' => $patient->name,
                    'mr_number' => $patient->mr_number,
                    'father_name' => $patient->father_name,
                    'age' => $patient->age,
                    'gender' => $patient->gender,
                    'phone' => $patient->phone,
                    'cnic' => $patient->cnic,
                    'status' => $patient->status,
                    'current_admission' => $admission
                ];
            }

            return response()->json(['data' => $result]);

        } catch (\Exception $e) {
            Log::error('Error searching patients: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Get Patient Details
    public function getPatientDetails($patientId)
    {
        try {
            $patient = Patient::findOrFail($patientId);
            
            $admission = PatientAdmission::where('patient_id', $patientId)
                ->where('status', 'active')
                ->with(['department', 'doctor', 'bed'])
                ->first();

            $attendants = \App\Models\Attendant::where('patient_id', $patientId)->get();
            $clearance = \App\Models\PatientClearance::where('patient_id', $patientId)->first();

            return response()->json([
                'id' => $patient->id,
                'name' => $patient->name,
                'mr_number' => $patient->mr_number,
                'father_name' => $patient->father_name,
                'age' => $patient->age,
                'gender' => $patient->gender,
                'phone' => $patient->phone,
                'cnic' => $patient->cnic,
                'address' => $patient->address,
                'status' => $patient->status,
                'current_admission' => $admission,
                'attendants' => $attendants,
                'clearance' => $clearance
            ]);

        } catch (\Exception $e) {
            Log::error('Error loading patient details: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Get Department Beds
    public function getDepartmentBeds($departmentId)
    {
        try {
            $department = Department::findOrFail($departmentId);
            $beds = Bed::where('department_id', $departmentId)->get();

            $result = [];
            foreach ($beds as $bed) {
                $admission = PatientAdmission::where('bed_id', $bed->id)
                    ->where('status', 'active')
                    ->with(['patient', 'doctor'])
                    ->first();

                $result[] = [
                    'id' => $bed->id,
                    'bed_number' => $bed->bed_number,
                    'status' => $bed->status,
                    'current_admission' => $admission
                ];
            }

            return response()->json([
                'id' => $department->id,
                'name' => $department->name,
                'location' => $department->location,
                'beds' => $result
            ]);

        } catch (\Exception $e) {
            Log::error('Error loading beds: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // Get Bed Details
    public function getBedDetails($bedId)
    {
        try {
            $bed = Bed::with('department')->findOrFail($bedId);
            
            $admission = PatientAdmission::where('bed_id', $bedId)
                ->where('status', 'active')
                ->with(['patient', 'doctor'])
                ->first();

            return response()->json([
                'id' => $bed->id,
                'bed_number' => $bed->bed_number,
                'status' => $bed->status,
                'department' => $bed->department,
                'current_admission' => $admission
            ]);

        } catch (\Exception $e) {
            Log::error('Error loading bed details: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}