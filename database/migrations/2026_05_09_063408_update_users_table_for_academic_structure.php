<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $box) {
            $box->foreignId('faculty_id')->nullable()->constrained()->onDelete('set null');
            $box->foreignId('department_id')->nullable()->constrained()->onDelete('set null');
            $box->string('phone')->nullable();
            $box->string('profile_photo')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $box) {
            $box->dropForeign(['faculty_id']);
            $box->dropForeign(['department_id']);
            $box->dropColumn(['faculty_id', 'department_id', 'phone', 'profile_photo']);
        });
    }
};
