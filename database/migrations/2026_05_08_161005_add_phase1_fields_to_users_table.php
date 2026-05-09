<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('lecturer')->after('email'); // admin, lecturer
            $table->foreignId('faculty_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('department_id')->nullable()->constrained()->onDelete('set null');
            $table->string('phone')->nullable();
            $table->string('profile_photo')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['faculty_id']);
            $table->dropForeign(['department_id']);
            $table->dropColumn(['role', 'faculty_id', 'department_id', 'phone', 'profile_photo']);
        });
    }
};
