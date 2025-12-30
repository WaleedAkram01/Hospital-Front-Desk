<!-- Patient Clearance Modal -->
<div id="clearanceModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-10 mx-auto p-5 border w-11/12 max-w-5xl shadow-lg rounded-md bg-white mb-10">

        <!-- Modal Header -->
        <div class="flex items-center justify-between border-b pb-4 mb-4">
            <h3 class="text-2xl font-bold text-gray-800">
                <i class="fas fa-clipboard-check text-teal-600 mr-2"></i>
                Patient Clearance Report
            </h3>
            <button onclick="closeModal('clearanceModal')" class="text-gray-600 hover:text-gray-800">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>

        <!-- Search Patient Section -->
        <div class="mb-6 bg-gradient-to-r from-teal-500 to-teal-600 text-white p-6 rounded-lg">
            <h4 class="text-lg font-bold mb-3">
                <i class="fas fa-search mr-2"></i>
                Search Patient for Clearance
            </h4>
            <p class="text-sm text-teal-100 mb-3">
                <strong>Try these samples:</strong> MR-2024-001, MR-2024-002, or phone: 0321-1234567
            </p>
            <div class="flex space-x-3">
                <input type="text" id="clearanceSearchInput"
                    placeholder="Enter MR Number (e.g., MR-2024-001) or Phone..."
                    class="flex-1 px-4 py-3 rounded-lg text-gray-800 focus:outline-none focus:ring-2 focus:ring-white">
                <button onclick="searchForClearance()"
                    class="bg-white text-teal-600 px-6 py-3 rounded-lg font-semibold hover:bg-teal-50 transition-colors">
                    <i class="fas fa-search mr-2"></i>Search
                </button>
            </div>
        </div>

        <!-- Clearance Details Section -->
        <div id="clearanceDetails" class="hidden space-y-6">

            <!-- Patient Info Card -->
            <div id="clearancePatientInfo" class="bg-gradient-to-r from-teal-600 to-teal-700 text-white p-6 rounded-lg">
            </div>

            <!-- Clearance Checklist -->
            <div class="bg-white border-2 border-teal-200 rounded-lg p-6">
                <h5 class="text-xl font-bold text-gray-800 mb-4">
                    <i class="fas fa-tasks text-teal-600 mr-2"></i>
                    Clearance Checklist
                </h5>

                <div class="space-y-3">
                    <!-- Billing -->
                    <div
                        class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-money-bill-wave text-2xl text-green-600"></i>
                            <div>
                                <p class="font-semibold text-gray-800">Billing Department</p>
                                <p class="text-sm text-gray-600">All payments cleared</p>
                            </div>
                        </div>
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" class="clearance-checkbox hidden" data-field="billing_cleared">
                            <div class="checkbox-custom w-12 h-6 bg-gray-300 rounded-full transition-all"></div>
                        </label>
                    </div>

                    <!-- Pharmacy -->
                    <div
                        class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-pills text-2xl text-purple-600"></i>
                            <div>
                                <p class="font-semibold text-gray-800">Pharmacy Department</p>
                                <p class="text-sm text-gray-600">All medications issued/returned</p>
                            </div>
                        </div>
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" class="clearance-checkbox hidden" data-field="pharmacy_cleared">
                            <div class="checkbox-custom w-12 h-6 bg-gray-300 rounded-full transition-all"></div>
                        </label>
                    </div>

                    <!-- Laboratory -->
                    <div
                        class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-vial text-2xl text-blue-600"></i>
                            <div>
                                <p class="font-semibold text-gray-800">Laboratory Department</p>
                                <p class="text-sm text-gray-600">All reports collected</p>
                            </div>
                        </div>
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" class="clearance-checkbox hidden" data-field="lab_cleared">
                            <div class="checkbox-custom w-12 h-6 bg-gray-300 rounded-full transition-all"></div>
                        </label>
                    </div>

                    <!-- Ward -->
                    <div
                        class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-bed text-2xl text-red-600"></i>
                            <div>
                                <p class="font-semibold text-gray-800">Ward Department</p>
                                <p class="text-sm text-gray-600">Bed vacated & property checked</p>
                            </div>
                        </div>
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" class="clearance-checkbox hidden" data-field="ward_cleared">
                            <div class="checkbox-custom w-12 h-6 bg-gray-300 rounded-full transition-all"></div>
                        </label>
                    </div>

                    <!-- Discharge Summary -->
                    <div
                        class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-file-medical text-2xl text-orange-600"></i>
                            <div>
                                <p class="font-semibold text-gray-800">Discharge Summary</p>
                                <p class="text-sm text-gray-600">Medical summary completed</p>
                            </div>
                        </div>
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" class="clearance-checkbox hidden" data-field="discharge_summary">
                            <div class="checkbox-custom w-12 h-6 bg-gray-300 rounded-full transition-all"></div>
                        </label>
                    </div>
                </div>

                <!-- Remarks -->
                <div class="mt-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Additional Remarks:</label>
                    <textarea id="clearanceRemarks" rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500"
                        placeholder="Any additional notes..."></textarea>
                </div>
            </div>

            <!-- Final Clearance Button -->
            <div id="finalClearanceSection" class="hidden">
                <div class="bg-green-50 border-2 border-green-300 rounded-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h5 class="text-xl font-bold text-green-800 mb-2">
                                <i class="fas fa-check-circle mr-2"></i>
                                Ready for Final Clearance
                            </h5>
                            <p class="text-sm text-green-700">All departments have cleared this patient</p>
                        </div>
                        <button onclick="markFinalClearance()"
                            class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-lg font-bold text-lg shadow-lg transform hover:scale-105 transition-all">
                            <i class="fas fa-check-double mr-2"></i>
                            Issue Final Clearance
                        </button>
                    </div>
                </div>
            </div>

            <!-- Already Cleared Message -->
            <div id="alreadyClearedSection" class="hidden">
                <div class="bg-blue-50 border-2 border-blue-300 rounded-lg p-6 text-center">
                    <i class="fas fa-check-circle text-6xl text-blue-600 mb-3"></i>
                    <h5 class="text-2xl font-bold text-blue-800 mb-2">Patient Already Cleared</h5>
                    <p class="text-blue-700">This patient has been cleared for discharge</p>
                </div>
            </div>

        </div>

    </div>
</div>

@push('styles')
    <style>
        .clearance-checkbox:checked+.checkbox-custom {
            background-color: #10b981;
        }

        .clearance-checkbox:checked+.checkbox-custom::after {
            content: '';
            position: absolute;
            right: 2px;
            top: 2px;
            width: 20px;
            height: 20px;
            background-color: white;
            border-radius: 50%;
            transition: all 0.3s;
        }

        .checkbox-custom {
            position: relative;
        }

        .checkbox-custom::after {
            content: '';
            position: absolute;
            left: 2px;
            top: 2px;
            width: 20px;
            height: 20px;
            background-color: white;
            border-radius: 50%;
            transition: all 0.3s;
        }
    </style>
@endpush

@push('scripts')
    <script>
        let currentClearancePatient = null;

        // Search for clearance
        $('#clearanceSearchInput').keypress(function(e) {
            if (e.which === 13) {
                searchForClearance();
            }
        });

        function searchForClearance() {
            const searchTerm = $('#clearanceSearchInput').val().trim();

            if (!searchTerm) {
                showAlert('Please enter MR Number or Phone', 'error');
                return;
            }

            // Show loading
            $('#clearanceDetails').addClass('hidden');

            $.get('/clearance/search', {
                mr_number: searchTerm
            }, function(response) {
                console.log('Clearance response:', response);

                if (response.success && response.patient) {
                    currentClearancePatient = response.patient;
                    displayClearanceInfo(response.patient);
                } else {
                    showAlert('Patient not found', 'error');
                }
            }).fail(function(xhr) {
                console.error('Clearance error:', xhr);
                const errorMsg = xhr.responseJSON?.message || 'Patient not found';
                showAlert(errorMsg, 'error');
                $('#clearanceDetails').addClass('hidden');
            });
        }

        // Display clearance information
        function displayClearanceInfo(patient) {
            // Patient Info
            const patientHtml = `
        <div>
            <h4 class="text-2xl font-bold mb-2">${patient.name}</h4>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-sm">
                <div>
                    <p class="text-teal-100">MR Number</p>
                    <p class="font-semibold">${patient.mr_number}</p>
                </div>
                <div>
                    <p class="text-teal-100">Age/Gender</p>
                    <p class="font-semibold">${patient.age} / ${patient.gender}</p>
                </div>
                <div>
                    <p class="text-teal-100">Phone</p>
                    <p class="font-semibold">${patient.phone}</p>
                </div>
                <div>
                    <p class="text-teal-100">Status</p>
                    <p class="font-semibold">${patient.status.toUpperCase()}</p>
                </div>
            </div>
        </div>
    `;
            $('#clearancePatientInfo').html(patientHtml);

            // Load clearance data
            if (patient.clearance) {
                const clearance = patient.clearance;

                // Set checkbox states
                $('.clearance-checkbox[data-field="billing_cleared"]').prop('checked', clearance.billing_cleared);
                $('.clearance-checkbox[data-field="pharmacy_cleared"]').prop('checked', clearance.pharmacy_cleared);
                $('.clearance-checkbox[data-field="lab_cleared"]').prop('checked', clearance.lab_cleared);
                $('.clearance-checkbox[data-field="ward_cleared"]').prop('checked', clearance.ward_cleared);
                $('.clearance-checkbox[data-field="discharge_summary"]').prop('checked', clearance.discharge_summary);

                $('#clearanceRemarks').val(clearance.remarks || '');

                // Check if all cleared
                const allCleared = clearance.billing_cleared && clearance.pharmacy_cleared &&
                    clearance.lab_cleared && clearance.ward_cleared &&
                    clearance.discharge_summary;

                if (clearance.final_clearance) {
                    $('#finalClearanceSection').addClass('hidden');
                    $('#alreadyClearedSection').removeClass('hidden');
                } else if (allCleared) {
                    $('#finalClearanceSection').removeClass('hidden');
                    $('#alreadyClearedSection').addClass('hidden');
                } else {
                    $('#finalClearanceSection, #alreadyClearedSection').addClass('hidden');
                }
            }

            $('#clearanceDetails').removeClass('hidden');
        }

        // Handle checkbox changes
        $(document).on('change', '.clearance-checkbox', function() {
            if (!currentClearancePatient) return;

            const field = $(this).data('field');
            const value = $(this).is(':checked');
            const remarks = $('#clearanceRemarks').val();

            const data = {
                [field]: value,
                remarks: remarks
            };

            $.post(`/clearance/${currentClearancePatient.id}`, data, function(response) {
                showAlert('Clearance updated', 'success');

                // Check if all cleared now
                const clearance = response.clearance;
                const allCleared = clearance.billing_cleared && clearance.pharmacy_cleared &&
                    clearance.lab_cleared && clearance.ward_cleared &&
                    clearance.discharge_summary;

                if (allCleared && !clearance.final_clearance) {
                    $('#finalClearanceSection').removeClass('hidden');
                } else {
                    $('#finalClearanceSection').addClass('hidden');
                }
            }).fail(function() {
                showAlert('Error updating clearance', 'error');
                $(this).prop('checked', !value);
            });
        });

        // Save remarks
        $('#clearanceRemarks').on('blur', function() {
            if (!currentClearancePatient) return;

            $.post(`/clearance/${currentClearancePatient.id}`, {
                remarks: $(this).val()
            }, function() {
                showAlert('Remarks saved', 'success');
            });
        });

        // Mark final clearance
        function markFinalClearance() {
            if (!currentClearancePatient) return;

            if (!confirm('Issue final clearance and mark patient as discharged?')) {
                return;
            }

            $.post(`/clearance/${currentClearancePatient.id}/final`, function(response) {
                showAlert(response.message, 'success');
                $('#finalClearanceSection').addClass('hidden');
                $('#alreadyClearedSection').removeClass('hidden');

                // Disable all checkboxes
                $('.clearance-checkbox').prop('disabled', true);
                $('#clearanceRemarks').prop('disabled', true);
            }).fail(function(xhr) {
                const error = xhr.responseJSON?.message || 'Error issuing final clearance';
                showAlert(error, 'error');
            });
        }
    </script>
@endpush
