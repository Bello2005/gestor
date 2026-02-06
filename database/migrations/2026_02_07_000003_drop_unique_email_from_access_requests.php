<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::statement('DROP INDEX IF EXISTS access_requests_email_unique');
    }

    public function down()
    {
        DB::statement('CREATE UNIQUE INDEX access_requests_email_unique ON access_requests (email)');
    }
};
