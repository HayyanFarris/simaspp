<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Tambahkan role setelah email
            $table->enum('role', ['admin', 'guru'])->default('guru')->after('email');
            // Tambahkan kelas_id (nullable) setelah role
            $table->foreignId('kelas_id')->nullable()->after('role')->constrained('kelas')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['kelas_id']);
            $table->dropColumn(['role', 'kelas_id']);
        });
    }
};
