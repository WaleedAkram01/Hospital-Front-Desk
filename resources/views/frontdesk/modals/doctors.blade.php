<!-- Doctors & OPD Modal -->
<div id="doctorsModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-6xl shadow-lg rounded-md bg-white">

        <!-- Modal Header -->
        <div class="flex items-center justify-between border-b pb-4 mb-4">
            <h3 class="text-2xl font-bold text-gray-800">
                <i class="fas fa-user-md text-blue-600 mr-2"></i>
                Doctors & OPD Schedule
            </h3>
            <button onclick="closeModal('doctorsModal')" class="text-gray-600 hover:text-gray-800">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>

        <!-- Specialization Selection -->
        <div class="mb-6">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Select Specialization:</label>
            <select id="specializationSelect"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">-- Choose Specialization --</option>
                @foreach ($specializations as $spec)
                    <option value="{{ $spec->id }}">{{ $spec->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Doctors List -->
        <div id="doctorsList" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="text-center text-gray-500 py-8 col-span-2">
                <i class="fas fa-info-circle text-4xl mb-2"></i>
                <p>Please select a specialization to view doctors</p>
            </div>
        </div>

    </div>
</div>

<!-- Doctor Details Modal -->
<div id="doctorDetailsModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-4xl shadow-lg rounded-md bg-white">

        <div class="flex items-center justify-between border-b pb-4 mb-4">
            <h3 class="text-2xl font-bold text-gray-800">Doctor Details</h3>
            <button onclick="closeModal('doctorDetailsModal')" class="text-gray-600 hover:text-gray-800">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>

        <div id="doctorDetailsContent"></div>

    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            // Load doctors when specialization is selected
            $('#specializationSelect').change(function() {
                const specializationId = $(this).val();

                if (!specializationId) {
                    $('#doctorsList').html(`
                <div class="text-center text-gray-500 py-8 col-span-2">
                    <i class="fas fa-info-circle text-4xl mb-2"></i>
                    <p>Please select a specialization to view doctors</p>
                </div>
            `);
                    return;
                }

                // Show loading
                $('#doctorsList').html(`
            <div class="text-center py-8 col-span-2">
                <i class="fas fa-spinner fa-spin text-4xl text-blue-600"></i>
                <p class="mt-2 text-gray-600">Loading doctors...</p>
            </div>
        `);

                // Fetch doctors
                $.get(`/doctors/specialization/${specializationId}`, function(doctors) {
                    if (doctors.length === 0) {
                        $('#doctorsList').html(`
                    <div class="text-center text-gray-500 py-8 col-span-2">
                        <i class="fas fa-user-md-slash text-4xl mb-2"></i>
                        <p>No doctors found for this specialization</p>
                    </div>
                `);
                        return;
                    }

                    let html = '';
                    doctors.forEach(doctor => {
                        const scheduleCount = doctor.active_schedules ? doctor
                            .active_schedules.length : 0;
                        html += `
                    <div class="bg-gradient-to-br from-blue-50 to-white border border-blue-200 rounded-lg p-4 hover:shadow-lg transition-all cursor-pointer" onclick="viewDoctorDetails(${doctor.id})">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex-1">
                                <h4 class="text-lg font-bold text-gray-800">${doctor.name}</h4>
                                <p class="text-sm text-gray-600">${doctor.qualification}</p>
                            </div>
                            <div class="bg-blue-600 text-white px-3 py-1 rounded-full text-sm font-semibold">
                                PKR ${parseFloat(doctor.consultation_fee).toLocaleString()}
                            </div>
                        </div>

                        <div class="space-y-2 text-sm">
                            ${doctor.phone ? `
                                        <div class="flex items-center text-gray-600">
                                            <i class="fas fa-phone w-5"></i>
                                            <span>${doctor.phone}</span>
                                        </div>
                                    ` : ''}

                            <div class="flex items-center text-gray-600">
                                <i class="fas fa-calendar-alt w-5"></i>
                                <span>${scheduleCount} OPD Day(s) Available</span>
                            </div>
                        </div>

                        <div class="mt-3 pt-3 border-t border-gray-200">
                            <button class="text-blue-600 hover:text-blue-800 font-semibold text-sm">
                                View Full Schedule <i class="fas fa-arrow-right ml-1"></i>
                            </button>
                        </div>
                    </div>
                `;
                    });

                    $('#doctorsList').html(html);
                }).fail(function() {
                    showAlert('Error loading doctors', 'error');
                });
            });
        });

        // View doctor details with full schedule
        function viewDoctorDetails(doctorId) {
            $.get(`/doctors/${doctorId}`, function(doctor) {
                let scheduleHtml = '';

                if (doctor.active_schedules && doctor.active_schedules.length > 0) {
                    doctor.active_schedules.forEach(schedule => {
                        const startTime = new Date('2000-01-01 ' + schedule.start_time).toLocaleTimeString(
                            'en-US', {
                                hour: '2-digit',
                                minute: '2-digit'
                            });
                        const endTime = new Date('2000-01-01 ' + schedule.end_time).toLocaleTimeString(
                            'en-US', {
                                hour: '2-digit',
                                minute: '2-digit'
                            });

                        scheduleHtml += `
                    <div class="flex items-center justify-between bg-gray-50 p-3 rounded">
                        <div class="flex items-center">
                            <i class="fas fa-calendar-day text-blue-600 mr-3"></i>
                            <span class="font-semibold">${schedule.day}</span>
                        </div>
                        <div class="text-gray-600">
                            <i class="far fa-clock mr-2"></i>
                            ${startTime} - ${endTime}
                        </div>
                    </div>
                `;
                    });
                } else {
                    scheduleHtml = '<p class="text-center text-gray-500 py-4">No schedule available</p>';
                }

                const html = `
            <div class="space-y-4">
                <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white p-6 rounded-lg">
                    <h4 class="text-2xl font-bold mb-2">${doctor.name}</h4>
                    <p class="text-blue-100">${doctor.qualification}</p>
                    <div class="mt-4 flex items-center space-x-6">
                        ${doctor.phone ? `
                                    <div class="flex items-center">
                                        <i class="fas fa-phone mr-2"></i>
                                        <span>${doctor.phone}</span>
                                    </div>
                                ` : ''}
                        ${doctor.email ? `
                                    <div class="flex items-center">
                                        <i class="fas fa-envelope mr-2"></i>
                                        <span>${doctor.email}</span>
                                    </div>
                                ` : ''}
                    </div>
                </div>

                <div class="bg-white border-2 border-blue-200 p-4 rounded-lg">
                    <div class="flex items-center justify-between">
                        <span class="text-gray-700 font-semibold">Consultation Fee:</span>
                        <span class="text-2xl font-bold text-blue-600">PKR ${parseFloat(doctor.consultation_fee).toLocaleString()}</span>
                    </div>
                </div>

                <div>
                    <h5 class="text-lg font-bold text-gray-800 mb-3">
                        <i class="fas fa-calendar-alt text-blue-600 mr-2"></i>
                        OPD Schedule
                    </h5>
                    <div class="space-y-2">
                        ${scheduleHtml}
                    </div>
                </div>
            </div>
        `;

                $('#doctorDetailsContent').html(html);
                openModal('doctorDetailsModal');
            }).fail(function() {
                showAlert('Error loading doctor details', 'error');
            });
        }
    </script>
@endpush
