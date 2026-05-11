<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('attendance_logs', function (Blueprint $table) {
            $table->decimal('attendance_mark', 3, 2)->default(0)->after('attendance_status');
            $table->integer('week_number')->nullable()->after('session_id');
            // Assuming semester_id as a string or int for now if no semesters table exists
            $table->string('semester_id')->nullable()->after('week_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendance_logs', function (Blueprint $table) {
            $table->dropColumn(['attendance_mark', 'week_number', 'semester_id']);
        });
    }
};
