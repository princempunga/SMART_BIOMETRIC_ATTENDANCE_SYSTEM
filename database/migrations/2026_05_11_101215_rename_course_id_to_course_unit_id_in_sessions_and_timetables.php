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
            $table->dropForeign(['course_id']);
            $table->renameColumn('course_id', 'course_unit_id');
            $table->foreign('course_unit_id')->references('id')->on('course_units')->onDelete('cascade');
        });

        Schema::table('timetables', function (Blueprint $table) {
            $table->dropForeign(['course_id']);
            $table->renameColumn('course_id', 'course_unit_id');
            $table->foreign('course_unit_id')->references('id')->on('course_units')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendance_sessions', function (Blueprint $table) {
            $table->dropForeign(['course_unit_id']);
            $table->renameColumn('course_unit_id', 'course_id');
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
        });

        Schema::table('timetables', function (Blueprint $table) {
            $table->dropForeign(['course_unit_id']);
            $table->renameColumn('course_unit_id', 'course_id');
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
        });
    }
};
