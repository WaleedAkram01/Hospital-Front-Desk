<?php

namespace App\Http\Controllers;

use App\Models\Attendant;
use App\Models\AttendantLog;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AttendantController extends Controller
{
    // Get Attendant by Card Number
    public function getAttendantByCard($cardNumber)
    {
        try {
            Log::info('Searching for card: ' . $cardNumber);

            $attendant = Attendant::with('patient')
                ->where('card_number', $cardNumber)
                ->first();

            if (!$attendant) {
                Log::error('Card not found: ' . $cardNumber);

                // Get all cards for debugging
                $allCards = Attendant::pluck('card_number');

                return response()->json([
                    'error' => 'Card not found',
                    'message' => 'No attendant found with card number: ' . $cardNumber,
                    'available_cards' => $allCards
                ], 404);
            }

            Log::info('Card found: ' . $cardNumber);
            return response()->json($attendant);

        } catch (\Exception $e) {
            Log::error('Attendant search error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Error loading attendant',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Create New Attendant
    public function storeAttendant(Request $request)
    {
        try {
            $validated = $request->validate([
                'patient_id' => 'required|exists:patients,id',
                'name' => 'required|string|max:255',
                'relation' => 'required|string|max:100',
                'cnic' => 'required|string|max:15',
                'phone' => 'required|string|max:20',
                'address' => 'nullable|string',
            ]);

            // Generate unique card number
            $validated['card_number'] = 'ATN-' . date('Y') . '-' . str_pad(Attendant::count() + 1, 3, '0', STR_PAD_LEFT);
            $validated['status'] = 'in';
            $validated['last_in_time'] = Carbon::now();

            $attendant = Attendant::create($validated);

            // Log the entry
            AttendantLog::create([
                'attendant_id' => $attendant->id,
                'action' => 'in',
                'action_time' => Carbon::now()
            ]);

            return response()->json([
                'success' => true,
                'attendant' => $attendant,
                'message' => 'Attendant registered successfully. Card Number: ' . $attendant->card_number
            ]);

        } catch (\Exception $e) {
            Log::error('Attendant creation error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Error creating attendant',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Mark Attendant IN/OUT
    public function toggleAttendantStatus(Request $request, $attendantId)
    {
        try {
            $attendant = Attendant::findOrFail($attendantId);

            $newStatus = $attendant->status === 'in' ? 'out' : 'in';
            $currentTime = Carbon::now();

            if ($newStatus === 'in') {
                $attendant->last_in_time = $currentTime;
            } else {
                $attendant->last_out_time = $currentTime;
            }

            $attendant->status = $newStatus;
            $attendant->save();

            // Log the action
            AttendantLog::create([
                'attendant_id' => $attendant->id,
                'action' => $newStatus,
                'action_time' => $currentTime,
                'remarks' => $request->remarks
            ]);

            return response()->json([
                'success' => true,
                'attendant' => $attendant,
                'message' => 'Status updated to ' . strtoupper($newStatus)
            ]);

        } catch (\Exception $e) {
            Log::error('Status toggle error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Error updating status',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Get Attendant Logs
    public function getAttendantLogs($attendantId)
    {
        try {
            $logs = AttendantLog::where('attendant_id', $attendantId)
                ->orderBy('action_time', 'desc')
                ->get();

            return response()->json($logs);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error loading logs',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Get Patient Attendants
    public function getPatientAttendants($patientId)
    {
        try {
            $attendants = Attendant::where('patient_id', $patientId)
                ->with('latestLog')
                ->get();

            return response()->json($attendants);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error loading attendants',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
