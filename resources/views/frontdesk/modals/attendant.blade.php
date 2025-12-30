<!-- Attendant Management Modal -->
<div id="attendantModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-10 mx-auto p-5 border w-11/12 max-w-5xl shadow-lg rounded-md bg-white mb-10">

        <!-- Modal Header -->
        <div class="flex items-center justify-between border-b pb-4 mb-4">
            <h3 class="text-2xl font-bold text-gray-800">
                <i class="fas fa-id-card text-indigo-600 mr-2"></i>
                Attendant IN/OUT Management
            </h3>
            <button onclick="closeModal('attendantModal')" class="text-gray-600 hover:text-gray-800">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>

        <!-- Card Scan Section -->
        <div class="mb-6 bg-gradient-to-r from-indigo-500 to-indigo-600 text-white p-6 rounded-lg">
            <h4 class="text-lg font-bold mb-3">
                <i class="fas fa-qrcode mr-2"></i>
                Scan Attendant Card
            </h4>
            <p class="text-sm text-indigo-100 mb-3">
                <strong>Try these sample cards:</strong> ATN-2024-001, ATN-2024-002, or ATN-2024-003
            </p>
            <div class="flex space-x-3">
                <input type="text" id="cardNumberInput" placeholder="Enter card number (e.g., ATN-1234567001)..."
                    class="flex-1 px-4 py-3 rounded-lg text-gray-800 focus:outline-none focus:ring-2 focus:ring-white"
                    autofocus>
                <button onclick="scanCard()"
                    class="bg-white text-indigo-600 px-6 py-3 rounded-lg font-semibold hover:bg-indigo-50 transition-colors">
                    <i class="fas fa-search mr-2"></i>Scan
                </button>
            </div>
        </div>

        <!-- Attendant Details Section -->
        <div id="attendantDetails" class="hidden">
            <div class="bg-white border-2 border-indigo-200 rounded-lg p-6 mb-4">
                <div id="attendantInfo"></div>
            </div>

            <!-- Action Buttons -->
            <div class="flex space-x-3">
                <button id="toggleStatusBtn" onclick="toggleAttendantStatus()"
                    class="flex-1 py-3 rounded-lg font-bold text-lg transition-all">
                </button>
                <button onclick="viewAttendantLogs()"
                    class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg font-semibold">
                    <i class="fas fa-history mr-2"></i>View History
                </button>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mt-6 pt-6 border-t">
            <button onclick="openNewAttendantForm()"
                class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-semibold">
                <i class="fas fa-plus mr-2"></i>Register New Attendant
            </button>
        </div>

    </div>
</div>

<!-- New Attendant Registration Modal -->
<div id="newAttendantModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-10 mx-auto p-5 border w-11/12 max-w-3xl shadow-lg rounded-md bg-white mb-10">

        <div class="flex items-center justify-between border-b pb-4 mb-4">
            <h3 class="text-2xl font-bold text-gray-800">Register New Attendant</h3>
            <button onclick="closeModal('newAttendantModal')" class="text-gray-600 hover:text-gray-800">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>

        <form id="newAttendantForm" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Patient MR Number *</label>
                    <input type="text" id="patientMrSearch" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <div id="patientSearchResult" class="mt-2 text-sm"></div>
                    <input type="hidden" id="selectedPatientId">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Attendant Name *</label>
                    <input type="text" id="attendantName" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Relation *</label>
                    <select id="attendantRelation" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">Select Relation</option>
                        <option value="Father">Father</option>
                        <option value="Mother">Mother</option>
                        <option value="Brother">Brother</option>
                        <option value="Sister">Sister</option>
                        <option value="Son">Son</option>
                        <option value="Daughter">Daughter</option>
                        <option value="Spouse">Spouse</option>
                        <option value="Friend">Friend</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">CNIC *</label>
                    <input type="text" id="attendantCnic" required placeholder="XXXXX-XXXXXXX-X"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Phone *</label>
                    <input type="text" id="attendantPhone" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Address</label>
                    <input type="text" id="attendantAddress"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>

            <div class="flex justify-end space-x-3 pt-4 border-t">
                <button type="button" onclick="closeModal('newAttendantModal')"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg">
                    Cancel
                </button>
                <button type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg font-semibold">
                    <i class="fas fa-save mr-2"></i>Register & Issue Card
                </button>
            </div>
        </form>

    </div>
</div>

<!-- Attendant Logs Modal -->
<div id="attendantLogsModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-10 mx-auto p-5 border w-11/12 max-w-4xl shadow-lg rounded-md bg-white mb-10">

        <div class="flex items-center justify-between border-b pb-4 mb-4">
            <h3 class="text-2xl font-bold text-gray-800">Attendant History</h3>
            <button onclick="closeModal('attendantLogsModal')" class="text-gray-600 hover:text-gray-800">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>

        <div id="attendantLogsContent"></div>

    </div>
</div>

@push('scripts')
    <script>
        let currentAttendant = null;

        // Scan card
        $('#cardNumberInput').keypress(function(e) {
            if (e.which === 13) {
                scanCard();
            }
        });

        function scanCard() {
            const cardNumber = $('#cardNumberInput').val().trim();

            if (!cardNumber) {
                showAlert('Please enter card number', 'error');
                return;
            }

            $.get(`/attendants/card/${cardNumber}`, function(attendant) {
                currentAttendant = attendant;
                displayAttendantInfo(attendant);
            }).fail(function(xhr) {
                showAlert('Card not found or invalid', 'error');
                $('#attendantDetails').addClass('hidden');
            });
        }

        // Display attendant information
        function displayAttendantInfo(attendant) {
            const html = `
        <div class="space-y-4">
            <div class="flex items-start justify-between">
                <div>
                    <h4 class="text-2xl font-bold text-gray-800">${attendant.name}</h4>
                    <p class="text-gray-600">${attendant.relation} of ${attendant.patient.name}</p>
                </div>
                <span class="px-4 py-2 rounded-full text-sm font-bold ${
                    attendant.status === 'in' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                }">
                    ${attendant.status === 'in' ? 'INSIDE HOSPITAL' : 'OUTSIDE'}
                </span>
            </div>

            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-gray-600">Card Number</p>
                    <p class="font-semibold text-lg">${attendant.card_number}</p>
                </div>
                <div>
                    <p class="text-gray-600">CNIC</p>
                    <p class="font-semibold">${attendant.cnic}</p>
                </div>
                <div>
                    <p class="text-gray-600">Phone</p>
                    <p class="font-semibold">${attendant.phone}</p>
                </div>
                <div>
                    <p class="text-gray-600">Patient MR#</p>
                    <p class="font-semibold">${attendant.patient.mr_number}</p>
                </div>
            </div>

            ${attendant.last_in_time || attendant.last_out_time ? `
                    <div class="bg-gray-50 p-3 rounded">
                        ${attendant.last_in_time ? `<p class="text-sm"><span class="font-semibold">Last IN:</span> ${new Date(attendant.last_in_time).toLocaleString()}</p>` : ''}
                        ${attendant.last_out_time ? `<p class="text-sm"><span class="font-semibold">Last OUT:</span> ${new Date(attendant.last_out_time).toLocaleString()}</p>` : ''}
                    </div>
                ` : ''}
        </div>
    `;

            $('#attendantInfo').html(html);

            // Update toggle button
            if (attendant.status === 'in') {
                $('#toggleStatusBtn').removeClass('bg-green-600 hover:bg-green-700')
                    .addClass('bg-red-600 hover:bg-red-700')
                    .html('<i class="fas fa-sign-out-alt mr-2"></i>Mark OUT');
            } else {
                $('#toggleStatusBtn').removeClass('bg-red-600 hover:bg-red-700')
                    .addClass('bg-green-600 hover:bg-green-700')
                    .html('<i class="fas fa-sign-in-alt mr-2"></i>Mark IN');
            }

            $('#attendantDetails').removeClass('hidden');
        }

        // Toggle attendant status
        function toggleAttendantStatus() {
            if (!currentAttendant) return;

            const newStatus = currentAttendant.status === 'in' ? 'OUT' : 'IN';
            const confirmMsg = `Mark this attendant as ${newStatus}?`;

            if (!confirm(confirmMsg)) return;

            $.post(`/attendants/${currentAttendant.id}/toggle`, function(response) {
                showAlert(response.message, 'success');
                currentAttendant = response.attendant;
                displayAttendantInfo(response.attendant);
                $('#cardNumberInput').val('').focus();
            }).fail(function() {
                showAlert('Error updating status', 'error');
            });
        }

        // View attendant logs
        function viewAttendantLogs() {
            if (!currentAttendant) return;

            $.get(`/attendants/${currentAttendant.id}/logs`, function(logs) {
                let html = `
            <div class="space-y-2">
                ${logs.length === 0 ? '<p class="text-center text-gray-500 py-8">No history available</p>' : ''}
        `;

                logs.forEach(log => {
                    const isIn = log.action === 'in';
                    html += `
                <div class="flex items-center justify-between bg-gray-50 p-3 rounded">
                    <div class="flex items-center">
                        <span class="w-16 px-3 py-1 rounded-full text-sm font-semibold ${
                            isIn ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                        }">
                            ${log.action.toUpperCase()}
                        </span>
                        <span class="ml-4 text-gray-700">${new Date(log.action_time).toLocaleString()}</span>
                    </div>
                    ${log.remarks ? `<span class="text-sm text-gray-600">${log.remarks}</span>` : ''}
                </div>
            `;
                });

                html += `</div>`;
                $('#attendantLogsContent').html(html);
                openModal('attendantLogsModal');
            });
        }

        // Open new attendant form
        function openNewAttendantForm() {
            $('#newAttendantForm')[0].reset();
            $('#selectedPatientId').val('');
            $('#patientSearchResult').html('');
            openModal('newAttendantModal');
        }

        // Search patient for new attendant
        $('#patientMrSearch').on('input', function() {
            const mrNumber = $(this).val().trim();

            if (mrNumber.length < 3) {
                $('#patientSearchResult').html('');
                return;
            }

            $.get('/patients/search', {
                search: mrNumber
            }, function(response) {
                if (response.data.length > 0) {
                    const patient = response.data[0];
                    $('#selectedPatientId').val(patient.id);
                    $('#patientSearchResult').html(`
                <p class="text-green-600">
                    <i class="fas fa-check-circle mr-1"></i>
                    Found: ${patient.name} (${patient.mr_number})
                </p>
            `);
                } else {
                    $('#patientSearchResult').html('<p class="text-red-600">Patient not found</p>');
                }
            });
        });

        // Submit new attendant form
        $('#newAttendantForm').submit(function(e) {
            e.preventDefault();

            const patientId = $('#selectedPatientId').val();
            if (!patientId) {
                showAlert('Please select a valid patient', 'error');
                return;
            }

            const data = {
                patient_id: patientId,
                name: $('#attendantName').val(),
                relation: $('#attendantRelation').val(),
                cnic: $('#attendantCnic').val(),
                phone: $('#attendantPhone').val(),
                address: $('#attendantAddress').val()
            };

            $.post('/attendants', data, function(response) {
                showAlert('Attendant registered successfully!', 'success');
                showAlert('Card Number: ' + response.attendant.card_number, 'info');
                closeModal('newAttendantModal');
                $('#cardNumberInput').val(response.attendant.card_number);
                scanCard();
            }).fail(function() {
                showAlert('Error registering attendant', 'error');
            });
        });
    </script>
@endpush
