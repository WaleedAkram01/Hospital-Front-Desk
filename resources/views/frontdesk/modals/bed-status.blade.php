<!-- Ward Bed Status Modal -->
<div id="bedStatusModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
    <div class="relative top-10 mx-auto p-5 border w-11/12 max-w-7xl shadow-lg rounded-md bg-white mb-10">
        
        <!-- Modal Header -->
        <div class="flex items-center justify-between border-b pb-4 mb-4">
            <h3 class="text-2xl font-bold text-gray-800">
                <i class="fas fa-bed text-red-600 mr-2"></i>
                Ward Bed Status
            </h3>
            <button onclick="closeModal('bedStatusModal')" class="text-gray-600 hover:text-gray-800">
                <i class="fas fa-times text-2xl"></i>
            </button>
        </div>

        <!-- Department Selection -->
        <div class="mb-6">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Select Ward/Department:</label>
            <select id="wardSelect" class="w-full md:w-1/2 px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                <option value="">-- Choose Ward/Department --</option>
                @foreach($departments as $dept)
                    <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- Bed Status Summary -->
        <div id="bedSummary" class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6 hidden">
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white p-4 rounded-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-90">Total Beds</p>
                        <p class="text-3xl font-bold" id="totalBeds">0</p>
                    </div>
                    <i class="fas fa-bed text-4xl opacity-50"></i>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-green-500 to-green-600 text-white p-4 rounded-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-90">Available</p>
                        <p class="text-3xl font-bold" id="availableBeds">0</p>
                    </div>
                    <i class="fas fa-check-circle text-4xl opacity-50"></i>
                </div>
            </div>
            
            <div class="bg-gradient-to-br from-red-500 to-red-600 text-white p-4 rounded-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-90">Occupied</p>
                        <p class="text-3xl font-bold" id="occupiedBeds">0</p>
                    </div>
                    <i class="fas fa-user-injured text-4xl opacity-50"></i>
                </div>
            </div>
        </div>

        <!-- Legend -->
        <div id="bedLegend" class="flex items-center space-x-6 mb-4 p-3 bg-gray-50 rounded-lg hidden">
            <div class="flex items-center">
                <div class="w-4 h-4 bg-green-500 rounded mr-2"></div>
                <span class="text-sm text-gray-700">Available</span>
            </div>
            <div class="flex items-center">
                <div class="w-4 h-4 bg-red-500 rounded mr-2"></div>
                <span class="text-sm text-gray-700">Occupied</span>
            </div>
            <div class="flex items-center">
                <div class="w-4 h-4 bg-gray-400 rounded mr-2"></div>
                <span class="text-sm text-gray-700">Maintenance</span>
            </div>
        </div>

        <!-- Beds Grid -->
        <div id="bedsGrid" class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
            <div class="text-center text-gray-500 py-8 col-span-full">
                <i class="fas fa-info-circle text-4xl mb-2"></i>
                <p>Please select a ward to view bed status</p>
            </div>
        </div>

    </div>
</div>

<!-- Bed Details Tooltip -->
<div id="bedTooltip" class="fixed hidden bg-white border-2 border-gray-300 rounded-lg shadow-xl p-4 z-50" style="max-width: 300px;">
    <div id="bedTooltipContent"></div>
</div>

@push('scripts')
<script>
let currentBeds = [];

// Load beds when department is selected
$('#wardSelect').change(function() {
    const departmentId = $(this).val();
    
    if (!departmentId) {
        $('#bedsGrid').html(`
            <div class="text-center text-gray-500 py-8 col-span-full">
                <i class="fas fa-info-circle text-4xl mb-2"></i>
                <p>Please select a ward to view bed status</p>
            </div>
        `);
        $('#bedSummary, #bedLegend').addClass('hidden');
        return;
    }

    // Show loading
    $('#bedsGrid').html(`
        <div class="text-center py-8 col-span-full">
            <i class="fas fa-spinner fa-spin text-4xl text-red-600"></i>
            <p class="mt-2 text-gray-600">Loading beds...</p>
        </div>
    `);

    // Fetch beds
    $.get(`/departments/${departmentId}/beds`, function(department) {
        console.log('Beds response:', department); // Debug log
        currentBeds = department.beds;
        displayBeds(department);
    }).fail(function(xhr) {
        console.error('Beds error:', xhr); // Debug log
        showAlert('Error loading beds', 'error');
        $('#bedsGrid').html(`
            <div class="text-center text-red-500 py-8 col-span-full">
                <i class="fas fa-exclamation-circle text-4xl mb-2"></i>
                <p>Error loading beds</p>
                <p class="text-sm">${xhr.responseJSON?.error || 'Unknown error'}</p>
            </div>
        `);
    });
});

// Display beds
function displayBeds(department) {
    const beds = department.beds;
    
    if (beds.length === 0) {
        $('#bedsGrid').html(`
            <div class="text-center text-gray-500 py-8 col-span-full">
                <i class="fas fa-bed-empty text-4xl mb-2"></i>
                <p>No beds found in this ward</p>
            </div>
        `);
        $('#bedSummary, #bedLegend').addClass('hidden');
        return;
    }

    // Calculate statistics
    const total = beds.length;
    const available = beds.filter(b => b.status === 'available').length;
    const occupied = beds.filter(b => b.status === 'occupied').length;

    // Update summary
    $('#totalBeds').text(total);
    $('#availableBeds').text(available);
    $('#occupiedBeds').text(occupied);
    $('#bedSummary, #bedLegend').removeClass('hidden');

    // Display beds
    let html = '';
    beds.forEach(bed => {
        const colorClass = 
            bed.status === 'available' ? 'bg-green-500 hover:bg-green-600' :
            bed.status === 'occupied' ? 'bg-red-500 hover:bg-red-600' :
            'bg-gray-400 hover:bg-gray-500';

        html += `
            <div class="bed-card ${colorClass} text-white rounded-lg p-4 cursor-pointer transform hover:scale-105 transition-all"
                 data-bed-id="${bed.id}"
                 onmouseenter="showBedTooltip(event, ${bed.id})"
                 onmouseleave="hideBedTooltip()">
                <div class="text-center">
                    <i class="fas fa-bed text-2xl mb-2"></i>
                    <p class="font-bold text-lg">${bed.bed_number}</p>
                    <p class="text-xs opacity-90">${bed.status.toUpperCase()}</p>
                </div>
            </div>
        `;
    });

    $('#bedsGrid').html(html);
}

// Show bed tooltip on hover
function showBedTooltip(event, bedId) {
    const bed = currentBeds.find(b => b.id === bedId);
    if (!bed) return;

    let content = `
        <div class="space-y-2">
            <h5 class="font-bold text-lg text-gray-800 border-b pb-2">
                <i class="fas fa-bed text-red-600 mr-2"></i>
                Bed ${bed.bed_number}
            </h5>
            <p class="text-sm">
                <span class="font-semibold">Status:</span> 
                <span class="px-2 py-1 rounded text-xs ${
                    bed.status === 'available' ? 'bg-green-100 text-green-800' :
                    bed.status === 'occupied' ? 'bg-red-100 text-red-800' :
                    'bg-gray-100 text-gray-800'
                }">${bed.status.toUpperCase()}</span>
            </p>
    `;

    if (bed.current_admission && bed.current_admission.patient) {
        const patient = bed.current_admission.patient;
        const doctor = bed.current_admission.doctor;
        
        content += `
            <div class="border-t pt-2 mt-2">
                <p class="font-semibold text-gray-800 mb-1">Patient Information:</p>
                <p class="text-sm"><span class="font-semibold">Name:</span> ${patient.name}</p>
                <p class="text-sm"><span class="font-semibold">MR#:</span> ${patient.mr_number}</p>
                <p class="text-sm"><span class="font-semibold">Age/Gender:</span> ${patient.age}/${patient.gender}</p>
                ${doctor ? `<p class="text-sm"><span class="font-semibold">Doctor:</span> ${doctor.name}</p>` : ''}
                <p class="text-sm"><span class="font-semibold">Admitted:</span> ${new Date(bed.current_admission.admission_date).toLocaleDateString()}</p>
            </div>
        `;
    }

    content += `</div>`;

    $('#bedTooltipContent').html(content);
    
    // Position tooltip
    const tooltip = $('#bedTooltip');
    tooltip.removeClass('hidden');
    
    const x = event.pageX + 15;
    const y = event.pageY - 10;
    
    tooltip.css({
        left: x + 'px',
        top: y + 'px'
    });
}

// Hide bed tooltip
function hideBedTooltip() {
    $('#bedTooltip').addClass('hidden');
}
</script>
@endpush