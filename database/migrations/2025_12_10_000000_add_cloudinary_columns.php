<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->string('proof_url')->nullable()->after('proof_path');
            $table->string('proof_public_id')->nullable()->after('proof_url');
        });

        Schema::table('info_files', function (Blueprint $table) {
            $table->string('file_url')->nullable()->after('file_path');
            $table->string('file_public_id')->nullable()->after('file_url');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['proof_url', 'proof_public_id']);
        });

        Schema::table('info_files', function (Blueprint $table) {
            $table->dropColumn(['file_url', 'file_public_id']);
        });
    }
};
