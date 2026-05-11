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
        Schema::table('classrooms', function (Blueprint $table) {
            $table->string('room_code')->after('room_name')->unique()->nullable();
            $table->string('building_name')->after('room_code')->nullable();
            $table->string('floor_number')->nullable()->after('building_name');
            $table->integer('seating_capacity')->after('floor_number')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active')->after('seating_capacity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('classrooms', function (Blueprint $table) {
            $table->dropColumn(['room_code', 'building_name', 'floor_number', 'seating_capacity', 'status']);
        });
    }
};
