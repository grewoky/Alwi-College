<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up()
    {
        // Use raw SQL to add the unique index to avoid Doctrine dependency.
        if (Schema::hasColumn('users', 'phone')) {
            try {
                DB::statement('ALTER TABLE `users` ADD UNIQUE INDEX `users_phone_unique` (`phone`)');
            } catch (\Exception $e) {
                // ignore failures (index may already exist or SQL error)
            }
        }
    }

    public function down()
    {
        try {
            DB::statement('ALTER TABLE `users` DROP INDEX `users_phone_unique`');
        } catch (\Exception $e) {
            // ignore failures
        }
    }
};
