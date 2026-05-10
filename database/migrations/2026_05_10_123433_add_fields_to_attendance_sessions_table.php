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
        Schema::table('attendance_sessions', function (Blueprint $table) {
            $table->foreignId('timetable_id')->nullable()->after('classroom_id')->constrained()->onDelete('set null');
            $table->integer('week_number')->after('timetable_id')->default(1);
            $table->string('status')->after('week_number')->default('pending'); // pending, active, completed, expired
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendance_sessions', function (Blueprint $table) {
            $table->dropForeign(['timetable_id']);
            $table->dropColumn(['timetable_id', 'week_number', 'status']);
        });
    }
};
