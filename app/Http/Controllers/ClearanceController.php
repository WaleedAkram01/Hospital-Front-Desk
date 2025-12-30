<?php

namespace App\Http\Controllers;

use App\Models\PatientClearance;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ClearanceController extends Controller
{
    // Get Patient Clearance Report
    public function getClearanceReport($patientId)
    {
        try {
            $patient = Patient::with([
                'clearance',
                'currentAdmission.department',
                'currentAdmission.doctor'
            ])->findOrFail($patientId);

            // Create clearance record if doesn't exist
            if (!$patient->clearance) {
                $clearance = PatientClearance::create([
                    'patient_id' => $patient->id
                ]);
                $patient->load('clearance');
            }

            return response()->json($patient);

        } catch (\Exception $e) {
            Log::error('Clearance report error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Error loading clearance',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Search Patient for Clearance by MR Number or Phone
    public function searchForClearance(Request $request)
    {
        try {
            $searchTerm = $request->mr_number;

            if (!$searchTerm) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please enter MR number or phone'
                ], 400);
            }

            Log::info('Clearance search for: ' . $searchTerm);

            // Clean search term
            $cleanSearch = str_replace(['-', ' ', '(', ')'], '', $searchTerm);

            // Try exact MR number match first
            $patient = Patient::with(['clearance', 'currentAdmission.department', 'currentAdmission.doctor', 'currentAdmission.bed'])
                ->where('mr_number', $searchTerm)
                ->first();

            // If not found, try phone match
            if (!$patient) {
                $patient = Patient::with(['clearance', 'currentAdmission.department', 'currentAdmission.doctor', 'currentAdmission.bed'])
                    ->where(function($query) use ($searchTerm, $cleanSearch) {
                        $query->where('phone', $searchTerm)
                              ->orWhere('phone', 'LIKE', '%' . $cleanSearch . '%')
                              ->orWhereRaw('REPLACE(REPLACE(REPLACE(phone, "-", ""), " ", ""), "(", "") LIKE ?', ['%' . $cleanSearch . '%']);
                    })
                    ->first();
            }

            // If still not found, try partial MR match
            if (!$patient) {
                $patient = Patient::with(['clearance', 'currentAdmission.department', 'currentAdmission.doctor', 'currentAdmission.bed'])
                    ->where('mr_number', 'LIKE', '%' . $searchTerm . '%')
                    ->first();
            }

            if (!$patient) {
                Log::error('Patient not found for clearance: ' . $searchTerm);

                return response()->json([
                    'success' => false,
                    'message' => 'Patient not found with: ' . $searchTerm . '. Try: MR-2024-001, MR-2024-002, or phone: 0321-1234567'
                ], 404);
            }

            // Create clearance if doesn't exist
            if (!$patient->clearance) {
                PatientClearance::create(['patient_id' => $patient->id]);
                $patient->load('clearance');
            }

            Log::info('Patient found for clearance: ' . $patient->name);

            return response()->json([
                'success' => true,
                'patient' => $patient
            ]);

        } catch (\Exception $e) {
            Log::error('Clearance search exception: ' . $e->getMessage() . ' at ' . $e->getFile() . ':' . $e->getLine());
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ], 500);
        }
    }

    // Update Clearance Status
    public function updateClearance(Request $request, $patientId)
    {
        try {
            $clearance = PatientClearance::where('patient_id', $patientId)->first();

            if (!$clearance) {
                $clearance = PatientClearance::create(['patient_id' => $patientId]);
            }

            $validated = $request->validate([
                'billing_cleared' => 'sometimes|boolean',
                'pharmacy_cleared' => 'sometimes|boolean',
                'lab_cleared' => 'sometimes|boolean',
                'ward_cleared' => 'sometimes|boolean',
                'discharge_summary' => 'sometimes|boolean',
                'remarks' => 'nullable|string'
            ]);

            $clearance->update($validated);

            // Check if all cleared
            if ($clearance->isClearanceComplete()) {
                $clearance->update(['final_clearance' => false]); // Set to false until final button clicked
            }

            return response()->json([
                'success' => true,
                'clearance' => $clearance,
                'message' => 'Clearance updated successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Clearance update error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Mark Final Clearance
    public function markFinalClearance($patientId)
    {
        try {
            $clearance = PatientClearance::where('patient_id', $patientId)->first();

            if (!$clearance || !$clearance->isClearanceComplete()) {
                return response()->json([
                    'success' => false,
                    'message' => 'All clearances must be completed first'
                ], 400);
            }

            $clearance->update(['final_clearance' => true]);

            // Update patient status
            $patient = Patient::find($patientId);
            $patient->update([
                'status' => 'discharged',
                'discharge_date' => now()
            ]);

            // Update patient admission
            $admission = $patient->currentAdmission;
            if ($admission) {
                $admission->update([
                    'status' => 'discharged',
                    'discharge_date' => now()
                ]);

                // Free up the bed
                if ($admission->bed) {
                    $admission->bed->update(['status' => 'available']);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Patient cleared for discharge'
            ]);

        } catch (\Exception $e) {
            Log::error('Final clearance error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
