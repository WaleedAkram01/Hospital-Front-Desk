<!-- Laboratory Tests Modal -->
<div id="testsModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-10 mx-auto p-5 border w-11/12 max-w-6xl shadow-lg rounded-md bg-white mb-10">

        <!-- Modal Header -->
        <div class="flex items-center justify-between border-b pb-4 mb-4">
            <h3 class="text-2xl font-bold text-gray-800">
                <i class="fas fa-vial text-green-600 mr-2"></i>
                Laboratory Tests
            </h3>
            <button onclick="closeModal('testsModal')" class="text-gray-600 hover:text-gray-800">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>

        <!-- Search and Filter -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Search Test:</label>
                <input type="text" id="testSearch" placeholder="Search by test name..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Filter by Department:</label>
                <select id="testDepartmentFilter"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option value="">All Departments</option>
                </select>
            </div>
        </div>

        <!-- Tests Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200">
                <thead class="bg-green-600 text-white">
                    <tr>
                        <th class="py-3 px-4 text-left">#</th>
                        <th class="py-3 px-4 text-left">Test Name</th>
                        <th class="py-3 px-4 text-left">Department</th>
                        <th class="py-3 px-4 text-right">Rate (PKR)</th>
                        <th class="py-3 px-4 text-center">Reporting Time</th>
                    </tr>
                </thead>
                <tbody id="testsTableBody">
                    <tr>
                        <td colspan="5" class="text-center py-8 text-gray-500">
                            <i class="fas fa-spinner fa-spin text-3xl mb-2"></i>
                            <p>Loading tests...</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Summary -->
        <div class="mt-4 p-4 bg-gray-50 rounded-lg flex items-center justify-between">
            <span class="text-gray-700 font-semibold">Total Tests:</span>
            <span id="totalTests" class="text-xl font-bold text-green-600">0</span>
        </div>

    </div>
</div>

@push('scripts')
    <script>
        let allTests = [];

        // Load tests when modal opens
        $('#testsModal').on('click', function(e) {
            if (e.target.id === 'testsModal') return;

            if (allTests.length === 0) {
                loadTests();
            }
        });

        // Load all tests
        function loadTests() {
            $.get('/tests', function(tests) {
                allTests = tests;

                // Get unique departments for filter
                const departments = [...new Set(tests.map(t => t.department).filter(d => d))];
                let deptOptions = '<option value="">All Departments</option>';
                departments.forEach(dept => {
                    deptOptions += `<option value="${dept}">${dept}</option>`;
                });
                $('#testDepartmentFilter').html(deptOptions);

                displayTests(tests);
            }).fail(function() {
                showAlert('Error loading tests', 'error');
            });
        }

        // Display tests in table
        function displayTests(tests) {
            if (tests.length === 0) {
                $('#testsTableBody').html(`
            <tr>
                <td colspan="5" class="text-center py-8 text-gray-500">
                    <i class="fas fa-inbox text-4xl mb-2"></i>
                    <p>No tests found</p>
                </td>
            </tr>
        `);
                $('#totalTests').text('0');
                return;
            }

            let html = '';
            tests.forEach((test, index) => {
                html += `
            <tr class="border-b hover:bg-gray-50 transition-colors">
                <td class="py-3 px-4">${index + 1}</td>
                <td class="py-3 px-4">
                    <div>
                        <p class="font-semibold text-gray-800">${test.name}</p>
                        ${test.description ? `<p class="text-sm text-gray-600">${test.description}</p>` : ''}
                    </div>
                </td>
                <td class="py-3 px-4">
                    ${test.department ? `<span class="bg-green-100 text-green-800 px-2 py-1 rounded text-sm">${test.department}</span>` : '-'}
                </td>
                <td class="py-3 px-4 text-right">
                    <span class="font-bold text-green-600">${parseFloat(test.rate).toLocaleString()}</span>
                </td>
                <td class="py-3 px-4 text-center">
                    <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">
                        ${test.reporting_time_hours}h
                    </span>
                </td>
            </tr>
        `;
            });

            $('#testsTableBody').html(html);
            $('#totalTests').text(tests.length);
        }

        // Search tests
        $('#testSearch').on('input', function() {
            filterTests();
        });

        // Filter by department
        $('#testDepartmentFilter').change(function() {
            filterTests();
        });

        function filterTests() {
            const searchTerm = $('#testSearch').val().toLowerCase();
            const department = $('#testDepartmentFilter').val();

            let filtered = allTests;

            if (searchTerm) {
                filtered = filtered.filter(test =>
                    test.name.toLowerCase().includes(searchTerm) ||
                    (test.description && test.description.toLowerCase().includes(searchTerm))
                );
            }

            if (department) {
                filtered = filtered.filter(test => test.department === department);
            }

            displayTests(filtered);
        }
    </script>
@endpush
