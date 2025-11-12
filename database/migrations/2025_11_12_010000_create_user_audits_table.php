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
        Schema::create('user_audits', function (Blueprint $table) {
            $table->id();
            $table->string('action'); // e.g. clear_email, delete_account
            $table->unsignedBigInteger('target_user_id')->nullable()->index();
            $table->unsignedBigInteger('target_student_id')->nullable()->index();
            $table->unsignedBigInteger('performed_by')->nullable()->index();
            $table->text('details')->nullable(); // JSON or text notes
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_audits');
    }
};
