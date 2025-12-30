@extends('layouts.app')

@section('title', 'Front Desk Dashboard')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

        <!-- 1. Doctors & Specializations Card -->
        <div class="bg-white rounded-lg shadow-md p-6 hover-scale cursor-pointer" onclick="openModal('doctorsModal')">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-xl font-bold text-gray-800">Doctors & OPD</h3>
                    <p class="text-sm text-gray-600">View specializations & schedules</p>
                </div>
                <div class="bg-blue-100 p-4 rounded-full">
                    <i class="fas fa-user-md text-3xl text-blue-600"></i>
                </div>
            </div>
            <div class="text-right">
                <button class="text-blue-600 hover:text-blue-800 font-semibold">
                    View Details <i class="fas fa-arrow-right ml-2"></i>
                </button>
            </div>
        </div>

        <!-- 2. Tests Card -->
        <div class="bg-white rounded-lg shadow-md p-6 hover-scale cursor-pointer" onclick="openModal('testsModal')">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-xl font-bold text-gray-800">Laboratory Tests</h3>
                    <p class="text-sm text-gray-600">All available tests & rates</p>
                </div>
                <div class="bg-green-100 p-4 rounded-full">
                    <i class="fas fa-vial text-3xl text-green-600"></i>
                </div>
            </div>
            <div class="text-right">
                <button class="text-green-600 hover:text-green-800 font-semibold">
                    View Tests <i class="fas fa-arrow-right ml-2"></i>
                </button>
            </div>
        </div>

        <!-- 3. Surgeries Card -->
        <div class="bg-white rounded-lg shadow-md p-6 hover-scale cursor-pointer" onclick="openModal('surgeriesModal')">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-xl font-bold text-gray-800">Surgeries</h3>
                    <p class="text-sm text-gray-600">Surgery rates & information</p>
                </div>
                <div class="bg-purple-100 p-4 rounded-full">
                    <i class="fas fa-procedures text-3xl text-purple-600"></i>
                </div>
            </div>
            <div class="text-right">
                <button class="text-purple-600 hover:text-purple-800 font-semibold">
                    View Surgeries <i class="fas fa-arrow-right ml-2"></i>
                </button>
            </div>
        </div>

        <!-- 4. Patient Search Card -->
        <div class="bg-white rounded-lg shadow-md p-6 hover-scale cursor-pointer" onclick="openModal('patientSearchModal')">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-xl font-bold text-gray-800">Patient Search</h3>
                    <p class="text-sm text-gray-600">Find admitted patients</p>
                </div>
                <div class="bg-yellow-100 p-4 rounded-full">
                    <i class="fas fa-search text-3xl text-yellow-600"></i>
                </div>
            </div>
            <div class="text-right">
                <button class="text-yellow-600 hover:text-yellow-800 font-semibold">
                    Search Patients <i class="fas fa-arrow-right ml-2"></i>
                </button>
            </div>
        </div>

        <!-- 5. Ward Bed Status Card -->
        <div class="bg-white rounded-lg shadow-md p-6 hover-scale cursor-pointer" onclick="openModal('bedStatusModal')">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-xl font-bold text-gray-800">Ward Bed Status</h3>
                    <p class="text-sm text-gray-600">Check bed availability</p>
                </div>
                <div class="bg-red-100 p-4 rounded-full">
                    <i class="fas fa-bed text-3xl text-red-600"></i>
                </div>
            </div>
            <div class="text-right">
                <button class="text-red-600 hover:text-red-800 font-semibold">
                    View Status <i class="fas fa-arrow-right ml-2"></i>
                </button>
            </div>
        </div>

        <!-- 6. Attendant Management Card -->
        <div class="bg-white rounded-lg shadow-md p-6 hover-scale cursor-pointer" onclick="openModal('attendantModal')">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-xl font-bold text-gray-800">Attendant IN/OUT</h3>
                    <p class="text-sm text-gray-600">Guardian card management</p>
                </div>
                <div class="bg-indigo-100 p-4 rounded-full">
                    <i class="fas fa-id-card text-3xl text-indigo-600"></i>
                </div>
            </div>
            <div class="text-right">
                <button class="text-indigo-600 hover:text-indigo-800 font-semibold">
                    Manage Cards <i class="fas fa-arrow-right ml-2"></i>
                </button>
            </div>
        </div>

        <!-- 7. Clearance Report Card -->
        <div class="bg-white rounded-lg shadow-md p-6 hover-scale cursor-pointer col-span-1 md:col-span-2 lg:col-span-3"
            onclick="openModal('clearanceModal')">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-xl font-bold text-gray-800">Patient Clearance Report</h3>
                    <p class="text-sm text-gray-600">Check discharge clearance status</p>
                </div>
                <div class="bg-teal-100 p-4 rounded-full">
                    <i class="fas fa-clipboard-check text-3xl text-teal-600"></i>
                </div>
            </div>
            <div class="text-right">
                <button class="text-teal-600 hover:text-teal-800 font-semibold">
                    Check Clearance <i class="fas fa-arrow-right ml-2"></i>
                </button>
            </div>
        </div>

    </div>

    <!-- Include All Modals -->
    @include('frontdesk.modals.doctors')
    @include('frontdesk.modals.tests')
    @include('frontdesk.modals.surgeries')
    @include('frontdesk.modals.patient-search')
    @include('frontdesk.modals.bed-status')
    @include('frontdesk.modals.attendant')
    @include('frontdesk.modals.clearance')

@endsection

@push('scripts')
    <script>
        // Pass specializations and departments to JavaScript
        const specializations = @json($specializations);
        const departments = @json($departments);
    </script>
@endpush
