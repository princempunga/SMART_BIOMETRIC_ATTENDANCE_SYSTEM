<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // Drop old string columns if they exist
            if (Schema::hasColumn('students', 'faculty')) {
                $table->dropColumn('faculty');
            }
            if (Schema::hasColumn('students', 'department')) {
                $table->dropColumn('department');
            }

            // Add new foreign key columns if they don't exist
            if (!Schema::hasColumn('students', 'faculty_id')) {
                $table->foreignId('faculty_id')->nullable()->after('reg_number')->constrained('faculties')->onDelete('set null');
            }
            if (!Schema::hasColumn('students', 'department_id')) {
                $table->foreignId('department_id')->nullable()->after('faculty_id')->constrained('departments')->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropForeign(['faculty_id']);
            $table->dropForeign(['department_id']);
            $table->dropColumn(['faculty_id', 'department_id']);
            $table->string('faculty')->after('reg_number')->nullable();
            $table->string('department')->after('faculty')->nullable();
        });
    }
};
