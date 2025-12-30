<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Specializations Table
        Schema::create('specializations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Doctors Table
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('specialization_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('qualification');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->decimal('consultation_fee', 10, 2);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Doctor OPD Schedule Table
        Schema::create('doctor_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('doctor_id')->constrained()->onDelete('cascade');
            $table->enum('day', ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']);
            $table->time('start_time');
            $table->time('end_time');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Tests Table
        Schema::create('tests', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('rate', 10, 2);
            $table->integer('reporting_time_hours')->default(24);
            $table->string('department')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Surgeries Table
        Schema::create('surgeries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('rate', 10, 2);
            $table->integer('estimated_duration_hours')->nullable();
            $table->string('department')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Departments/Wards Table
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('location')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Beds Table
        Schema::create('beds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('department_id')->constrained()->onDelete('cascade');
            $table->string('bed_number');
            $table->enum('status', ['available', 'occupied', 'maintenance'])->default('available');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Patients Table
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('mr_number')->unique();
            $table->string('name');
            $table->string('father_name')->nullable();
            $table->integer('age');
            $table->enum('gender', ['Male', 'Female', 'Other']);
            $table->string('phone');
            $table->string('cnic')->nullable();
            $table->text('address')->nullable();
            $table->date('admission_date')->nullable();
            $table->date('discharge_date')->nullable();
            $table->enum('status', ['admitted', 'discharged', 'opd'])->default('opd');
            $table->timestamps();
        });

        // Patient Admissions Table
        Schema::create('patient_admissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->foreignId('bed_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('doctor_id')->constrained()->onDelete('cascade');
            $table->foreignId('department_id')->constrained()->onDelete('cascade');
            $table->date('admission_date');
            $table->date('discharge_date')->nullable();
            $table->text('reason')->nullable();
            $table->text('diagnosis')->nullable();
            $table->enum('status', ['active', 'discharged'])->default('active');
            $table->timestamps();
        });

        // Attendants/Guardians Table
        Schema::create('attendants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('relation');
            $table->string('cnic');
            $table->string('phone');
            $table->text('address')->nullable();
            $table->string('card_number')->unique();
            $table->enum('status', ['in', 'out'])->default('in');
            $table->timestamp('last_in_time')->nullable();
            $table->timestamp('last_out_time')->nullable();
            $table->timestamps();
        });

        // Attendant Logs (IN/OUT Tracking)
        Schema::create('attendant_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attendant_id')->constrained()->onDelete('cascade');
            $table->enum('action', ['in', 'out']);
            $table->timestamp('action_time');
            $table->text('remarks')->nullable();
            $table->timestamps();
        });

        // Patient Clearance Table
        Schema::create('patient_clearances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade');
            $table->boolean('billing_cleared')->default(false);
            $table->boolean('pharmacy_cleared')->default(false);
            $table->boolean('lab_cleared')->default(false);
            $table->boolean('ward_cleared')->default(false);
            $table->boolean('discharge_summary')->default(false);
            $table->boolean('final_clearance')->default(false);
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('patient_clearances');
        Schema::dropIfExists('attendant_logs');
        Schema::dropIfExists('attendants');
        Schema::dropIfExists('patient_admissions');
        Schema::dropIfExists('patients');
        Schema::dropIfExists('beds');
        Schema::dropIfExists('departments');
        Schema::dropIfExists('surgeries');
        Schema::dropIfExists('tests');
        Schema::dropIfExists('doctor_schedules');
        Schema::dropIfExists('doctors');
        Schema::dropIfExists('specializations');
    }
};