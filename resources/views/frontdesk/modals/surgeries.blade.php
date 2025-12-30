<!-- Surgeries Modal -->
<div id="surgeriesModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-10 mx-auto p-5 border w-11/12 max-w-6xl shadow-lg rounded-md bg-white mb-10">

        <!-- Modal Header -->
        <div class="flex items-center justify-between border-b pb-4 mb-4">
            <h3 class="text-2xl font-bold text-gray-800">
                <i class="fas fa-procedures text-purple-600 mr-2"></i>
                Surgeries & Procedures
            </h3>
            <button onclick="closeModal('surgeriesModal')" class="text-gray-600 hover:text-gray-800">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>

        <!-- Search and Filter -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Search Surgery:</label>
                <input type="text" id="surgerySearch" placeholder="Search by surgery name..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Filter by Department:</label>
                <select id="surgeryDepartmentFilter"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                    <option value="">All Departments</option>
                </select>
            </div>
        </div>

        <!-- Surgeries Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200">
                <thead class="bg-purple-600 text-white">
                    <tr>
                        <th class="py-3 px-4 text-left">#</th>
                        <th class="py-3 px-4 text-left">Surgery Name</th>
                        <th class="py-3 px-4 text-left">Department</th>
                        <th class="py-3 px-4 text-right">Rate (PKR)</th>
                        <th class="py-3 px-4 text-center">Duration</th>
                    </tr>
                </thead>
                <tbody id="surgeriesTableBody">
                    <tr>
                        <td colspan="5" class="text-center py-8 text-gray-500">
                            <i class="fas fa-spinner fa-spin text-3xl mb-2"></i>
                            <p>Loading surgeries...</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Summary -->
        <div class="mt-4 p-4 bg-gray-50 rounded-lg flex items-center justify-between">
            <span class="text-gray-700 font-semibold">Total Surgeries:</span>
            <span id="totalSurgeries" class="text-xl font-bold text-purple-600">0</span>
        </div>

    </div>
</div>

@push('scripts')
    <script>
        let allSurgeries = [];

        // Load surgeries when modal opens
        $('#surgeriesModal').on('click', function(e) {
            if (e.target.id === 'surgeriesModal') return;

            if (allSurgeries.length === 0) {
                loadSurgeries();
            }
        });

        // Load all surgeries
        function loadSurgeries() {
            $.get('/surgeries', function(surgeries) {
                allSurgeries = surgeries;

                // Get unique departments for filter
                const departments = [...new Set(surgeries.map(s => s.department).filter(d => d))];
                let deptOptions = '<option value="">All Departments</option>';
                departments.forEach(dept => {
                    deptOptions += `<option value="${dept}">${dept}</option>`;
                });
                $('#surgeryDepartmentFilter').html(deptOptions);

                displaySurgeries(surgeries);
            }).fail(function() {
                showAlert('Error loading surgeries', 'error');
            });
        }

        // Display surgeries in table
        function displaySurgeries(surgeries) {
            if (surgeries.length === 0) {
                $('#surgeriesTableBody').html(`
            <tr>
                <td colspan="5" class="text-center py-8 text-gray-500">
                    <i class="fas fa-inbox text-4xl mb-2"></i>
                    <p>No surgeries found</p>
                </td>
            </tr>
        `);
                $('#totalSurgeries').text('0');
                return;
            }

            let html = '';
            surgeries.forEach((surgery, index) => {
                html += `
            <tr class="border-b hover:bg-gray-50 transition-colors">
                <td class="py-3 px-4">${index + 1}</td>
                <td class="py-3 px-4">
                    <div>
                        <p class="font-semibold text-gray-800">${surgery.name}</p>
                        ${surgery.description ? `<p class="text-sm text-gray-600">${surgery.description}</p>` : ''}
                    </div>
                </td>
                <td class="py-3 px-4">
                    ${surgery.department ? `<span class="bg-purple-100 text-purple-800 px-2 py-1 rounded text-sm">${surgery.department}</span>` : '-'}
                </td>
                <td class="py-3 px-4 text-right">
                    <span class="font-bold text-purple-600">${parseFloat(surgery.rate).toLocaleString()}</span>
                </td>
                <td class="py-3 px-4 text-center">
                    ${surgery.estimated_duration_hours ? 
                        `<span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">${surgery.estimated_duration_hours}h</span>` 
                        : '-'}
                </td>
            </tr>
        `;
            });

            $('#surgeriesTableBody').html(html);
            $('#totalSurgeries').text(surgeries.length);
        }

        // Search surgeries
        $('#surgerySearch').on('input', function() {
            filterSurgeries();
        });

        // Filter by department
        $('#surgeryDepartmentFilter').change(function() {
            filterSurgeries();
        });

        function filterSurgeries() {
            const searchTerm = $('#surgerySearch').val().toLowerCase();
            const department = $('#surgeryDepartmentFilter').val();

            let filtered = allSurgeries;

            if (searchTerm) {
                filtered = filtered.filter(surgery =>
                    surgery.name.toLowerCase().includes(searchTerm) ||
                    (surgery.description && surgery.description.toLowerCase().includes(searchTerm))
                );
            }

            if (department) {
                filtered = filtered.filter(surgery => surgery.department === department);
            }

            displaySurgeries(filtered);
        }
    </script>
@endpush
