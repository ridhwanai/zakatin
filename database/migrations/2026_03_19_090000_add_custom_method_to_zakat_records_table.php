<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::getConnection()->getDriverName() !== 'mysql') {
            return;
        }

        DB::statement("ALTER TABLE `zakat_records` MODIFY `method` ENUM('rice', 'money', 'custom') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::getConnection()->getDriverName() !== 'mysql') {
            return;
        }

        DB::table('zakat_records')
            ->where('method', 'custom')
            ->update(['method' => 'money']);

        DB::statement("ALTER TABLE `zakat_records` MODIFY `method` ENUM('rice', 'money') NOT NULL");
    }
};
