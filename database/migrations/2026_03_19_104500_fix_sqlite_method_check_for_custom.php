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
        if (Schema::getConnection()->getDriverName() !== 'sqlite') {
            return;
        }

        $createSql = DB::table('sqlite_master')
            ->where('type', 'table')
            ->where('name', 'zakat_records')
            ->value('sql');

        if (! is_string($createSql) || str_contains($createSql, "'custom'")) {
            return;
        }

        DB::statement('PRAGMA foreign_keys=OFF');

        try {
            DB::statement(
                <<<'SQL'
                CREATE TABLE "zakat_records_new" (
                    "id" integer primary key autoincrement not null,
                    "project_id" integer not null,
                    "name" varchar not null,
                    "people_count" integer not null,
                    "method" varchar check ("method" in ('rice', 'money', 'custom')) not null,
                    "rice_kg" numeric,
                    "fitrah_money" numeric,
                    "wajib_money" numeric not null default '0',
                    "infaq_money" numeric not null default '0',
                    "mal_money" numeric not null default '0',
                    "created_at" datetime,
                    "updated_at" datetime,
                    foreign key("project_id") references "projects"("id") on delete cascade
                )
                SQL
            );

            DB::statement(
                <<<'SQL'
                INSERT INTO "zakat_records_new" (
                    "id",
                    "project_id",
                    "name",
                    "people_count",
                    "method",
                    "rice_kg",
                    "fitrah_money",
                    "wajib_money",
                    "infaq_money",
                    "mal_money",
                    "created_at",
                    "updated_at"
                )
                SELECT
                    "id",
                    "project_id",
                    "name",
                    "people_count",
                    "method",
                    "rice_kg",
                    "fitrah_money",
                    "wajib_money",
                    "infaq_money",
                    "mal_money",
                    "created_at",
                    "updated_at"
                FROM "zakat_records"
                SQL
            );

            DB::statement('DROP TABLE "zakat_records"');
            DB::statement('ALTER TABLE "zakat_records_new" RENAME TO "zakat_records"');
            DB::statement('CREATE INDEX "zakat_records_project_id_index" ON "zakat_records" ("project_id")');
        } finally {
            DB::statement('PRAGMA foreign_keys=ON');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::getConnection()->getDriverName() !== 'sqlite') {
            return;
        }

        $createSql = DB::table('sqlite_master')
            ->where('type', 'table')
            ->where('name', 'zakat_records')
            ->value('sql');

        if (! is_string($createSql) || ! str_contains($createSql, "'custom'")) {
            return;
        }

        DB::statement('PRAGMA foreign_keys=OFF');

        try {
            DB::statement(
                <<<'SQL'
                CREATE TABLE "zakat_records_old" (
                    "id" integer primary key autoincrement not null,
                    "project_id" integer not null,
                    "name" varchar not null,
                    "people_count" integer not null,
                    "method" varchar check ("method" in ('rice', 'money')) not null,
                    "rice_kg" numeric,
                    "fitrah_money" numeric,
                    "wajib_money" numeric not null default '0',
                    "infaq_money" numeric not null default '0',
                    "mal_money" numeric not null default '0',
                    "created_at" datetime,
                    "updated_at" datetime,
                    foreign key("project_id") references "projects"("id") on delete cascade
                )
                SQL
            );

            DB::statement(
                <<<'SQL'
                INSERT INTO "zakat_records_old" (
                    "id",
                    "project_id",
                    "name",
                    "people_count",
                    "method",
                    "rice_kg",
                    "fitrah_money",
                    "wajib_money",
                    "infaq_money",
                    "mal_money",
                    "created_at",
                    "updated_at"
                )
                SELECT
                    "id",
                    "project_id",
                    "name",
                    "people_count",
                    CASE WHEN "method" = 'custom' THEN 'money' ELSE "method" END as "method",
                    "rice_kg",
                    "fitrah_money",
                    "wajib_money",
                    "infaq_money",
                    "mal_money",
                    "created_at",
                    "updated_at"
                FROM "zakat_records"
                SQL
            );

            DB::statement('DROP TABLE "zakat_records"');
            DB::statement('ALTER TABLE "zakat_records_old" RENAME TO "zakat_records"');
            DB::statement('CREATE INDEX "zakat_records_project_id_index" ON "zakat_records" ("project_id")');
        } finally {
            DB::statement('PRAGMA foreign_keys=ON');
        }
    }
};
