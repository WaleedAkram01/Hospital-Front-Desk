<!-- Patient Search Modal -->
<div id="patientSearchModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-10 mx-auto p-5 border w-11/12 max-w-6xl shadow-lg rounded-md bg-white mb-10">

        <!-- Modal Header -->
        <div class="flex items-center justify-between border-b pb-4 mb-4">
            <h3 class="text-2xl font-bold text-gray-800">
                <i class="fas fa-search text-yellow-600 mr-2"></i>
                Patient Search
            </h3>
            <button onclick="closeModal('patientSearchModal')" class="text-gray-600 hover:text-gray-800">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>

        <!-- Search Form -->
        <div class="mb-6 bg-gray-50 p-4 rounded-lg">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Search Patient:</label>
                    <input type="text" id="patientSearchInput"
                        placeholder="Search by Name, MR Number, Phone, or CNIC..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Status Filter:</label>
                    <select id="patientStatusFilter"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-yellow-500">
                        <option value="">All Statuses</option>
                        <option value="admitted">Admitted</option>
                        <option value="opd">OPD</option>
                        <option value="discharged">Discharged</option>
                    </select>
                </div>
            </div>
            <div class="mt-3">
                <button onclick="searchPatients()"
                    class="bg-yellow-600 hover:bg-yellow-700 text-white px-6 py-2 rounded-lg font-semibold">
                    <i class="fas fa-search mr-2"></i>Search
                </button>
            </div>
        </div>

        <!-- Search Results -->
        <div id="patientSearchResults" class="space-y-3">
            <div class="text-center text-gray-500 py-8">
                <i class="fas fa-info-circle text-4xl mb-2"></i>
                <p>Enter search criteria to find patients</p>
            </div>
        </div>

    </div>
</div>

<!-- Patient Details Modal -->
<div id="patientDetailsModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-10 mx-auto p-5 border w-11/12 max-w-5xl shadow-lg rounded-md bg-white mb-10">

        <div class="flex items-center justify-between border-b pb-4 mb-4">
            <h3 class="text-2xl font-bold text-gray-800">Patient Details</h3>
            <button onclick="closeModal('patientDetailsModal')" class="text-gray-600 hover:text-gray-800">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>

        <div id="patientDetailsContent"></div>

    </div>
</div>

@push('scripts')
    <script>
        // Search patients
        function searchPatients() {
            const searchTerm = $('#patientSearchInput').val().trim();
            const status = $('#patientStatusFilter').val();

            if (!searchTerm && !status) {
                showAlert('Please enter search criteria', 'error');
                return;
            }

            // Show loading
            $('#patientSearchResults').html(`
        <div class="text-center py-8">
            <i class="fas fa-spinner fa-spin text-4xl text-yellow-600"></i>
            <p class="mt-2 text-gray-600">Searching patients...</p>
        </div>
    `);

            const params = {};
            if (searchTerm) params.search = searchTerm;
            if (status) params.status = status;

            $.get('/patients/search', params, function(response) {
                console.log('Patient search response:', response); // Debug log

                // Handle different response formats
                let patients;
                if (response.data) {
                    patients = response.data;
                } else if (Array.isArray(response)) {
                    patients = response;
                } else {
                    patients = [];
                }

                displayPatients(patients);
            }).fail(function(xhr) {
                console.error('Patient search error:', xhr); // Debug log
                showAlert('Error searching patients', 'error');
                $('#patientSearchResults').html(`
            <div class="text-center text-red-500 py-8">
                <i class="fas fa-exclamation-circle text-4xl mb-2"></i>
                <p>Error: ${xhr.responseJSON?.error || 'Unknown error'}</p>
                <p class="text-sm mt-2">${xhr.responseJSON?.message || ''}</p>
            </div>
        `);
            });
        }

        // Enter key to search
        $('#patientSearchInput').keypress(function(e) {
            if (e.which === 13) {
                searchPatients();
            }
        });

        // Display patient search results
        function displayPatients(patients) {
            // Handle if patients is wrapped in data object
            if (patients.data) {
                patients = patients.data;
            }

            if (!patients || patients.length === 0) {
                $('#patientSearchResults').html(`
            <div class="text-center text-gray-500 py-8">
                <i class="fas fa-user-slash text-4xl mb-2"></i>
                <p>No patients found</p>
            </div>
        `);
                return;
            }

            let html = '';
            patients.forEach(patient => {
                const statusColors = {
                    'admitted': 'bg-red-100 text-red-800',
                    'opd': 'bg-blue-100 text-blue-800',
                    'discharged': 'bg-green-100 text-green-800'
                };

                const statusColor = statusColors[patient.status] || 'bg-gray-100 text-gray-800';

                html += `
            <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-lg transition-all cursor-pointer" 
                 onclick="viewPatientDetails(${patient.id})">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center space-x-3 mb-2">
                            <h4 class="text-lg font-bold text-gray-800">${patient.name}</h4>
                            <span class="px-3 py-1 rounded-full text-sm font-semibold ${statusColor}">
                                ${patient.status.toUpperCase()}
                            </span>
                        </div>
                        
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-2 text-sm text-gray-600">
                            <div>
                                <i class="fas fa-id-card w-5"></i>
                                <span class="font-semibold">MR#:</span> ${patient.mr_number}
                            </div>
                            <div>
                                <i class="fas fa-user w-5"></i>
                                <span class="font-semibold">Age:</span> ${patient.age} / ${patient.gender}
                            </div>
                            <div>
                                <i class="fas fa-phone w-5"></i>
                                <span>${patient.phone}</span>
                            </div>
                            ${patient.current_admission ? `
                                    <div>
                                        <i class="fas fa-bed w-5"></i>
                                        <span class="font-semibold">Ward:</span> ${patient.current_admission.department.name}
                                    </div>
                                ` : ''}
                        </div>
                    </div>
                    
                    <button class="text-yellow-600 hover:text-yellow-800">
                        <i class="fas fa-arrow-right text-xl"></i>
                    </button>
                </div>
            </div>
        `;
            });

            $('#patientSearchResults').html(html);
        }

        // View patient full details
        function viewPatientDetails(patientId) {
            $.get(`/patients/${patientId}`, function(patient) {
                let html = `
            <div class="space-y-6">
                <!-- Patient Info Card -->
                <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 text-white p-6 rounded-lg">
                    <div class="flex items-start justify-between">
                        <div>
                            <h4 class="text-2xl font-bold mb-2">${patient.name}</h4>
                            <div class="space-y-1 text-yellow-100">
                                <p><span class="font-semibold">MR Number:</span> ${patient.mr_number}</p>
                                <p><span class="font-semibold">Father Name:</span> ${patient.father_name || 'N/A'}</p>
                                <p><span class="font-semibold">Age/Gender:</span> ${patient.age} years / ${patient.gender}</p>
                            </div>
                        </div>
                        <span class="bg-white text-yellow-600 px-4 py-2 rounded-full text-sm font-bold">
                            ${patient.status.toUpperCase()}
                        </span>
                    </div>
                </div>

                <!-- Contact Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h5 class="font-bold text-gray-700 mb-2">Contact Information</h5>
                        <p class="text-sm"><i class="fas fa-phone text-blue-600 mr-2"></i>${patient.phone}</p>
                        ${patient.cnic ? `<p class="text-sm mt-1"><i class="fas fa-id-card text-blue-600 mr-2"></i>${patient.cnic}</p>` : ''}
                        ${patient.address ? `<p class="text-sm mt-1"><i class="fas fa-map-marker-alt text-blue-600 mr-2"></i>${patient.address}</p>` : ''}
                    </div>
                    
                    ${patient.current_admission ? `
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h5 class="font-bold text-gray-700 mb-2">Current Admission</h5>
                                <p class="text-sm"><span class="font-semibold">Department:</span> ${patient.current_admission.department.name}</p>
                                <p class="text-sm"><span class="font-semibold">Bed:</span> ${patient.current_admission.bed ? patient.current_admission.bed.bed_number : 'N/A'}</p>
                                <p class="text-sm"><span class="font-semibold">Doctor:</span> ${patient.current_admission.doctor.name}</p>
                                <p class="text-sm"><span class="font-semibold">Admitted:</span> ${new Date(patient.current_admission.admission_date).toLocaleDateString()}</p>
                            </div>
                        ` : ''}
                </div>

                <!-- Attendants -->
                ${patient.attendants && patient.attendants.length > 0 ? `
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h5 class="font-bold text-gray-700 mb-3">
                                <i class="fas fa-users text-indigo-600 mr-2"></i>
                                Registered Attendants (${patient.attendants.length})
                            </h5>
                            <div class="space-y-2">
                                ${patient.attendants.map(att => `
                                <div class="flex items-center justify-between bg-white p-3 rounded">
                                    <div>
                                        <p class="font-semibold">${att.name} (${att.relation})</p>
                                        <p class="text-sm text-gray-600">Card: ${att.card_number}</p>
                                    </div>
                                    <span class="px-3 py-1 rounded-full text-sm font-semibold ${
                                        att.status === 'in' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'
                                    }">
                                        ${att.status.toUpperCase()}
                                    </span>
                                </div>
                            `).join('')}
                            </div>
                        </div>
                    ` : ''}
            </div>
        `;

                $('#patientDetailsContent').html(html);
                openModal('patientDetailsModal');
            }).fail(function() {
                showAlert('Error loading patient details', 'error');
            });
        }
    </script>
@endpush
