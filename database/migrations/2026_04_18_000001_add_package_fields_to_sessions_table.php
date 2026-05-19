<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPackageFieldsToSessionsTable extends Migration
{
    public function up()
    {
        Schema::table('sessions', function (Blueprint $table) {
            $table->enum('billing_type', ['free', 'package'])->default('free')->after('status');
            $table->unsignedInteger('package_minutes')->default(0)->after('billing_type');
            $table->dateTime('package_end_time')->nullable()->after('package_minutes');
        });
    }

    public function down()
    {
        Schema::table('sessions', function (Blueprint $table) {
            $table->dropColumn(['billing_type', 'package_minutes', 'package_end_time']);
        });
    }
}